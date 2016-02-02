Files:
 - import.php
	This php script will read out a publicly available google sheet and print out a list of 
		KEY, VALUE, LANGUAGE
	lines.
 - composer.json 
	You can use this file to install (or use in your own setup)
 - README.md
	You can read this file for more information. 

What should you do with it?
 - Change the import.php file so that it saves all the entries in a database of your choice.
 - Create a REST endpoint that you can fetch a key for a certain language.

     For example:
     Input: KEY_ARE_YOU_SURE, en
     Output: Are you sure?
	
     Input: KEY_ARE_YOU_SURE, nl
     Output: Bent u zeker?

 - Make it available publicly so we can check it out.
 - Share the code with us! 

Enjoy!
