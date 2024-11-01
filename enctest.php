<?php
/**
 * Created by PhpStorm.
 * User: notes
 * Date: 16-Sep-16
 * Time: 12:14 PM
 */

$public_key="ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDaPtZR9jk4liVRyPuMhxZrqaMDP9S+BfPDVth8JpgZADdX87hJK59cuiLR/kOurYLTyu3D2Fbg0MDMqRqCjCwUYPCr4UfAHBe+aPR/o6bb6VfBtVcHCzJMrHgnvAfQk4Ioa5OQctGqKylER4/++/4A2RhtTMTnd9lOXv7y+mAACeDDeXgd9e224SmQthrutVVwq768JmCzvmuXg93kbaV8cMD0QyvfW2JJgwIJcRTeKXc5B+5eTgcJChuQuOUkQu3JHaC6yBjEegkwZTGt3UPzmWHvQVkiDoghSTBlBt7AkTJBn95WQvuQTzLWRaKcX+SAvXij0tyAzQjScUHgfPA3";
//$public_key = $public_key["key"];

$text = 'This is the text to encrypt';

echo "This is the original text: $text\n\n";

// Encrypt using the public key
openssl_public_encrypt($text, $encrypted, $public_key);

$encrypted_hex = bin2hex(  $encrypted);
echo "This is the encrypted text: $encrypted_hex\n\n";

// Decrypt the data using the private key
openssl_private_decrypt($encrypted, $decrypted, $private_key);

echo "This is the decrypted text: $decrypted\n\n";