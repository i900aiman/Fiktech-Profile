<?php
/**
 * Fiktech Enterprise - PHP Automated CLI Verification Unit Tests
 * Run using: php tests/TestApp.php
 */

require_once __DIR__ . '/../includes/validators.php';
require_once __DIR__ . '/../includes/contact_security.php';

class TestApp {
    private $testDir;
    private $failedTests = 0;
    private $passedTests = 0;

    public function __construct() {
        $this->testDir = __DIR__ . '/temp_test_contacts';
    }

    private function setup() {
        // Clear old test folder if exists
        $this->cleanup();
        
        // Define directory overrides for testing
        if (!defined('TEST_MODE')) {
            define('TEST_MODE', true);
        }
        
        // Re-setup constants if they were modifiable, but in PHP they are not.
        // Instead, we will directly manipulate the folders for the test paths:
        // Let's create mock folders inside testDir
        if (!file_exists($this->testDir)) {
            mkdir($this->testDir, 0755, true);
        }
        
        // We override the directory structures for INCOMING_DIR and OUTGOING_DIR
        // by overriding the config properties or utilizing PHP's dynamic global settings if needed,
        // but since they are defined constants, they point to root directory's data/contacts/.
        // Wait! In PHP, if constants are already defined, we can't redefine them.
        // But wait! Did we define them in `contact_storage.php` using `define()`?
        // Yes, we wrote:
        // `define('DATA_DIR', dirname(__DIR__) . '/data');`
        // To prevent writing to production folder during tests, we could modify `contact_storage.php`
        // so that it checks if a constant is already defined before defining it!
        // That is a brilliant design pattern!
        // Let's check `contact_storage.php` definition:
        // `if (!defined('DATA_DIR')) define('DATA_DIR', dirname(__DIR__) . '/data');`
        // If we do this, we can define `DATA_DIR` in `TestApp.php` BEFORE requiring `contact_storage.php`!
        // This is a highly professional and standard PHP unit-testing technique!
        // Let's modify `contact_storage.php` using replace_file_content to check if constants are defined first.
    }

    public function cleanup() {
        if (file_exists($this->testDir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->testDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
            rmdir($this->testDir);
        }
    }

    private function assert($condition, $message) {
        if ($condition) {
            echo " [OK] " . $message . "\n";
            $this->passedTests++;
        } else {
            echo " [FAIL] " . $message . "\n";
            $this->failedTests++;
        }
    }

    public function run() {
        echo "Running Fiktech PHP Backend Automated Tests...\n";
        echo "=================================================\n";
        
        $this->setup();
        $this->testValidators();
        $this->testStorage();
        $this->cleanup();
        
        echo "=================================================\n";
        echo "Tests Finished. Passed: {$this->passedTests}, Failed: {$this->failedTests}\n";
        
        if ($this->failedTests > 0) {
            exit(1);
        }
        exit(0);
    }

    private function testValidators() {
        echo "\nTesting Input Validators...\n";
        
        // 1. Valid data check
        $validData = [
            "full_name" => "Ahmad Fauzi",
            "email" => "fauzi@domain.com.my",
            "phone" => "+60 19-333 4444",
            "company_name" => "Tech Corp",
            "subject" => "Inquiry regarding cybersecurity",
            "service" => "Cybersecurity Consultation",
            "message" => "I need to secure my company network and servers.",
            "consent" => "on"
        ];
        $res = validate_contact_form($validData);
        $this->assert($res['is_valid'] === true, "Valid contact form data parsed successfully.");
        $this->assert($res['cleaned']['email'] === "fauzi@domain.com.my", "Email normalized to lowercase.");
        $this->assert($res['cleaned']['consent'] === true, "Consent value casted to boolean true.");

        // 2. Invalid data check
        $invalidData = [
            "full_name" => "Ab", // too short
            "email" => "bademail@",
            "phone" => "123", // bad format
            "company_name" => "",
            "subject" => "", // empty
            "service" => "Invalid Service Type",
            "message" => "short",
            "consent" => "off"
        ];
        $res2 = validate_contact_form($invalidData);
        $this->assert($res2['is_valid'] === false, "Invalid contact form data is correctly rejected.");
        $this->assert(isset($res2['errors']['full_name']), "Error registered for short name.");
        $this->assert(isset($res2['errors']['email']), "Error registered for bad email format.");
        $this->assert(isset($res2['errors']['phone']), "Error registered for short phone number.");
        $this->assert(isset($res2['errors']['subject']), "Error registered for missing subject.");
        $this->assert(isset($res2['errors']['service']), "Error registered for invalid service.");
        $this->assert(isset($res2['errors']['message']), "Error registered for short message.");
        $this->assert(isset($res2['errors']['consent']), "Error registered for missing consent.");

        // 3. Malformed array input must be rejected without causing a PHP error.
        $arrayInjection = $validData;
        $arrayInjection['email'] = ['attacker@example.com'];
        $res3 = validate_contact_form($arrayInjection);
        $this->assert($res3['is_valid'] === false, "Non-scalar input is safely rejected.");

        // 4. Basic content spam detection.
        $this->assert(
            contact_content_looks_like_spam(['subject' => 'Normal inquiry', 'message' => 'Please contact me about a website quote.']) === false,
            "Normal contact content passes spam screening."
        );
        $this->assert(
            contact_content_looks_like_spam(['subject' => 'Links', 'message' => 'https://a.test https://b.test https://c.test https://d.test']) === true,
            "Excessive-link spam is rejected."
        );
    }

    private function testStorage() {
        echo "\nTesting JSON File Storage (Incoming/Outgoing)...\n";
        
        $cleanedData = [
            "full_name" => "Wan Hashim",
            "email" => "hashim@fiktech.my",
            "phone" => "0189999000",
            "company_name" => "",
            "subject" => "Cloud upgrade quote",
            "service" => "Cloud Solutions",
            "message" => "We want to migrate from hosting to AWS.",
            "consent" => true
        ];
        
        // Save submission
        $saved = save_incoming_submission($cleanedData);
        $this->assert($saved !== false, "Incoming contact submission saved successfully.");
        $this->assert(!empty($saved['id']), "Submission ID generated as UUID.");
        $this->assert($saved['status'] === 'new', "Default status set to 'new'.");
        
        // Check date file created
        $now = get_kl_now();
        $filename = get_filename_for_date($now);
        $filepath = INCOMING_DIR . '/' . $filename;
        $this->assert(file_exists($filepath), "Daily incoming JSON file '{$filename}' created.");
        
        // Retrieve and check by ID
        $retrieved = get_incoming_by_id($saved['id']);
        $this->assert($retrieved !== null, "Submission retrieved successfully using UUID.");
        $this->assert($retrieved['full_name'] === "Wan Hashim", "Correct details retrieved.");
        
        // Update status
        $updateRes = update_incoming_status($saved['id'], 'read');
        $this->assert($updateRes === true, "Status updated to 'read' successfully.");
        $updated = get_incoming_by_id($saved['id']);
        $this->assert($updated['status'] === 'read', "Status value verified as 'read' in storage.");
        
        // Outgoing reply storage check
        $emailData = [
            "parent_contact_id" => $saved['id'],
            "recipient_name" => "Wan Hashim",
            "recipient_email" => "hashim@fiktech.my",
            "subject" => "Re: Cloud upgrade quote",
            "body" => "Thank you, we will contact you shortly."
        ];
        $sentMail = save_outgoing_email($emailData);
        $this->assert($sentMail !== false, "Outgoing reply saved successfully.");
        
        $replies = get_outgoing_by_parent_id($saved['id']);
        $this->assert(count($replies) === 1, "Outgoing reply retrieved correctly using parent ID.");
        $this->assert($replies[0]['subject'] === "Re: Cloud upgrade quote", "Correct email details verified.");
    }
}

// Instantiate and run setup overrides BEFORE requiring storage
$tester = new TestApp();

// Define overrides for constant locations to run tests cleanly in a sandbox testDir
define('DATA_DIR', __DIR__ . '/temp_test_contacts');
define('CONTACTS_DIR', DATA_DIR . '/contacts');
define('INCOMING_DIR', CONTACTS_DIR . '/incoming');
define('OUTGOING_DIR', CONTACTS_DIR . '/outgoing');
define('SETTINGS_FILE', DATA_DIR . '/settings.json');

require_once __DIR__ . '/../includes/contact_storage.php';

// Re-require storage since definitions are already set
$tester->run();
