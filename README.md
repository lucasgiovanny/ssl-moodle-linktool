# HTTP to HTTPS URL Checker Moodle

This tool helps you to find all the URLs in your moodledata folder.

## Generate the report file

1. Open Linux terminal off your server
1. Navigate to moodledata/filedir folder
1. Run the following bash script

   `grep -Eorn "(http|https)://[a-zA-Z0-9./?=_-]*" . > report.txt`
   
## Configure the tool

1. Open the file `db.json`
1. Edit `servername`, `username`, `password` and `database` with you Moodle database connection

## Generate the report

1. Move the `report.txt` file generated on step 1 to the same folder
1. Open the `index.php`
1. Click on "Generate Report" button.
