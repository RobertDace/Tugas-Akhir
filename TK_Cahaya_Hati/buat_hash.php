<?php
$passwordAsli = 'tes123'; // Pastikan password ini yang ingin dibuatkan hash
$hash = password_hash($passwordAsli, PASSWORD_DEFAULT);

echo "<h1>Pembuat Hash Password</h1>";
echo "<p>Gunakan hash di bawah ini untuk dimasukkan ke dalam kolom 'password' di database Anda.</p>";
echo "<hr>";
echo "<p><strong>Password Asli:</strong> " . htmlspecialchars($passwordAsli) . "</p>";
echo "<p><strong>Hash yang Dihasilkan:</strong></p>";
echo "<textarea rows='3' cols='70' readonly>" . htmlspecialchars($hash) . "</textarea>";
?>