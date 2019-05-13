# HTTP to HTTPS URL Checker Moodle

This tool helps you to find all the URLs in your moodledata folder.

##Generate the report file

1. Open Linux terminal off your server
2. Navigate to moodledata/filedir folder
3. Run the following bash script

   `grep -Eorn "(http|https)://[a-zA-Z0-9./?=_-]*" . > report.txt`
