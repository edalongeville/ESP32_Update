# ESP32_Test_Update
Simple code to test remote update on an ESP32 module. 

## Content
The ino file contains the code running on the ESP32.  
The update.php file is destined to run on a web server.  
update_original.php contains the code provided by Espressif (not functional).

## Usage
Update $localBinary and $latestVersion in the update.php file. 

Deploy the update.php script onto the web server, deploy the .bin file to ./bin/<FILENAME>.bin.  

Deploy .ino onto the ESP32. Ensure all required libraries are installed, and update the necessary variables at the begining of the ino file.

Execute the code. The bin file deployed to the server will be installed on the ESP32.