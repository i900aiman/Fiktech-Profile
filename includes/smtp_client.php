<?php
/**
 * Fiktech Enterprise - Native SMTP Socket Client
 * Fully functional SMTP protocol implementation using fsockopen sockets.
 * Supports TLS, SSL, AUTH LOGIN, and standard HTML headers.
 */

class SmtpClient {
    private $host;
    private $port;
    private $username;
    private $password;
    private $secure; // 'tls', 'ssl', or 'none'
    private $timeout = 15;
    
    private $socket = null;
    private $logs = [];

    public function __construct($config) {
        $this->host = $config['smtp_host'] ?? '';
        $this->port = intval($config['smtp_port'] ?? 587);
        $this->username = $config['smtp_user'] ?? '';
        $this->password = $config['smtp_pass'] ?? '';
        $this->secure = strtolower($config['smtp_secure'] ?? 'tls');
    }

    private function log($message) {
        $this->logs[] = $message;
    }

    public function getLogs() {
        return $this->logs;
    }

    private function getResponse() {
        $response = '';
        while ($line = fgets($this->socket, 512)) {
            $response .= $line;
            // The 4th character of an SMTP response is a space if it's the last line, or a dash if it continues.
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $this->log("S: " . trim($response));
        return $response;
    }

    private function sendCommand($command, $sensitive = false) {
        $this->log("C: " . ($sensitive ? '[REDACTED]' : trim($command)));
        fwrite($this->socket, $command . "\r\n");
    }

    private function cleanHeaderValue($value) {
        return trim(str_replace(["\r", "\n"], '', (string) $value));
    }

    private function encodeHeader($value) {
        $value = $this->cleanHeaderValue($value);
        if (function_exists('mb_encode_mimeheader')) {
            return mb_encode_mimeheader($value, 'UTF-8', 'B', "\r\n");
        }
        return '=?UTF-8?B?' . base64_encode($value) . '?=';
    }

    public function send($toEmail, $toName, $subject, $htmlBody, $fromEmail, $fromName, $replyToEmail = '', $replyToName = '') {
        $this->logs = []; // Clear logs

        $toEmail = $this->cleanHeaderValue($toEmail);
        $fromEmail = $this->cleanHeaderValue($fromEmail);
        $replyToEmail = $this->cleanHeaderValue($replyToEmail);
        $toName = $this->cleanHeaderValue($toName);
        $fromName = $this->cleanHeaderValue($fromName);
        $replyToName = $this->cleanHeaderValue($replyToName);
        $subject = $this->cleanHeaderValue($subject);

        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $this->log('Invalid sender or recipient email address.');
            return false;
        }

        $serverName = preg_replace('/[^a-z0-9.-]/i', '', $_SERVER['SERVER_NAME'] ?? '');
        if (empty($serverName)) {
            $serverName = 'localhost';
        }
        
        $connectionHost = $this->host;
        if ($this->secure === 'ssl') {
            $connectionHost = 'ssl://' . $this->host;
        }

        $this->log("Connecting to {$connectionHost}:{$this->port}");
        $this->socket = @fsockopen($connectionHost, $this->port, $errno, $errstr, $this->timeout);
        
        if (!$this->socket) {
            $this->log("Connection failed: {$errstr} ({$errno})");
            return false;
        }

        stream_set_timeout($this->socket, $this->timeout);
        
        // 1. Read initial welcome message
        $response = $this->getResponse();
        if (strpos($response, '220') !== 0) {
            $this->close();
            return false;
        }

        // 2. Say HELO/EHLO
        $this->sendCommand("EHLO " . $serverName);
        $response = $this->getResponse();
        if (strpos($response, '250') !== 0) {
            $this->close();
            return false;
        }

        // 3. Handle TLS Upgrade
        if ($this->secure === 'tls') {
            $this->sendCommand("STARTTLS");
            $response = $this->getResponse();
            if (strpos($response, '220') !== 0) {
                $this->close();
                return false;
            }

            // Enable crypto on the socket connection
            $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            // PHP 7.2+ supports TLS 1.2 and 1.3 client methods
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $cryptoMethod = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
                if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                    $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
                }
            }

            if (!stream_socket_enable_crypto($this->socket, true, $cryptoMethod)) {
                $this->log("TLS encryption handshake failed.");
                $this->close();
                return false;
            }
            $this->log("TLS handshake successful.");

            // Resend EHLO now that connection is encrypted
            $this->sendCommand("EHLO " . $serverName);
            $response = $this->getResponse();
            if (strpos($response, '250') !== 0) {
                $this->close();
                return false;
            }
        }

        // 4. Authenticate if username is provided
        if (!empty($this->username)) {
            $this->sendCommand("AUTH LOGIN");
            $response = $this->getResponse();
            if (strpos($response, '334') !== 0) {
                $this->close();
                return false;
            }

            $this->sendCommand(base64_encode($this->username), true);
            $response = $this->getResponse();
            if (strpos($response, '334') !== 0) {
                $this->close();
                return false;
            }

            $this->sendCommand(base64_encode($this->password), true);
            $response = $this->getResponse();
            if (strpos($response, '235') !== 0) {
                $this->log("SMTP authentication failed.");
                $this->close();
                return false;
            }
        }

        // 5. Send MAIL FROM
        $this->sendCommand("MAIL FROM:<{$fromEmail}>");
        $response = $this->getResponse();
        if (strpos($response, '250') !== 0) {
            $this->close();
            return false;
        }

        // 6. Send RCPT TO
        $this->sendCommand("RCPT TO:<{$toEmail}>");
        $response = $this->getResponse();
        if (strpos($response, '250') !== 0) {
            $this->close();
            return false;
        }

        // 7. Send DATA
        $this->sendCommand("DATA");
        $response = $this->getResponse();
        if (strpos($response, '354') !== 0) {
            $this->close();
            return false;
        }

        // 8. Construct MIME Headers & Send Email Body
        $messageId = sprintf("<%s.%s@%s>", bin2hex(random_bytes(8)), uniqid(), $serverName);
        $date = date('r');
        
        $headers = [
            "Date: {$date}",
            "To: " . $this->encodeHeader($toName) . " <{$toEmail}>",
            "From: " . $this->encodeHeader($fromName) . " <{$fromEmail}>",
            "Subject: " . $this->encodeHeader($subject),
            "Message-ID: {$messageId}",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=UTF-8",
            "Content-Transfer-Encoding: base64",
            "X-Mailer: Fiktech SMTP Mailer v1.0",
        ];

        if (!empty($replyToEmail) && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
            $headers[] = "Reply-To: " . $this->encodeHeader($replyToName ?: $replyToEmail) . " <{$replyToEmail}>";
        }

        $headers[] = ""; // Empty line separating headers and body

        // RFC 2045 requires encoded MIME body lines to stay within 76 characters.
        // This also prevents strict providers such as Yahoo from rejecting long
        // minified HTML lines with a "lines too long for transport" bounce.
        $encodedBody = rtrim(chunk_split(base64_encode($htmlBody), 76, "\r\n"), "\r\n");
        $payload = implode("\r\n", $headers) . "\r\n" . $encodedBody;
        
        // Double periods at start of lines for SMTP transparent safety
        $payload = preg_replace('/^\./m', '..', $payload);
        
        // Ensure mail ends with \r\n.\r\n
        $this->sendCommand($payload . "\r\n.", true);
        
        $response = $this->getResponse();
        if (strpos($response, '250') !== 0) {
            $this->close();
            return false;
        }

        // 9. Send QUIT
        $this->sendCommand("QUIT");
        $this->getResponse();
        
        $this->close();
        return true;
    }

    private function close() {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
            $this->log("Connection closed.");
        }
    }
}
