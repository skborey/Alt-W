step 1: copy sendmail to
  C:\wamp64\
step 2: edit mail in sendmail.ini
step 3: edit php.ini in 
  C:\wamp64\bin\apache\apache2.4.27\bin 
  C:\wamp64\bin\php\php7.1.
  edit to sendmail_path = "C:\wamp64\sendmail\sendmail.exe -t -i"
step 4: edit my.in in 
  cd C:\wamp64\bin\mysql\mysql5.7.19\
  edit to myisam_sort_buffer_size = 150M
          max_allowed_packet = 64M
Note: 
  cd C:\wamp64\bin\mysql\mysql5.7.19\bin
  mysql -u root -p wordnet < index.sql