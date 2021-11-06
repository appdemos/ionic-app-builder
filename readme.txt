=== INSTALLATION ===

Before proceeding with your first Ionic App Builder installation, you should review the minimum requirements and best practices. Most web hosts meet these requirements. 

The base URL for your Ionic App Builder installation is set in your web server's configuration file. You need to know this URL before proceeding to the next steps of the installation.

If you are installing Ionic App Builder on your local machine, the base URL may be http://localhost.
If you're installing Ionic App Builder to a web server, your base URL may be a specific domain name, such as http://your-domain.com.

1. Extracting files
2. Upload/Copy to your web hosts folder
3. Permission on /projects/, /output/ and /system/class/jsmIonic.php should be 644 [-rw-r--r--].
4. Open web browser and go to http://localhost/setup.php
5. Check the configuration of your web hosting ()
6. If there are red or not suitable please adjust the requirements.
7. Go to Tab Product Activation, fill Envato Purchase Code and valid email (email use for get vip membership in our helpdesk).
8. Then Click Register

HOW TO ENABLE CORS
* http://enable-cors.org/server.html

FREE CORS PROXY
* http://cors.io/?u=http://your-link
* https://crossorigin.me/http://your-link


== HELPDESK ==

Support
- info@ihsana.com
- info@ihsana.net

Before submit a ticket please Open this URL:

Archive
- http://archive.ihsana.net/



POPULAR NPM ISSUE

Set Proxy:
$ npm config set proxy http://192.168.0.1:3129
$ npm config set https-proxy http://192.168.0.1:3129


SET "DEFAULT_JVM_OPTS=^"-Dhttp.proxyHost=192.168.0.1 -Dhttp.proxyPort=3128^""


$ platforms\android\gradlew -Dhttps.proxyHost=192.168.0.1 -Dhttps.proxyPort=3129
$ platforms\android\gradlew -Dhttp.proxyHost=192.168.0.1 -Dhttp.proxyPort=3129


$ nano platforms/android/project.properties
systemProp.http.proxyHost=192.168.0.1
systemProp.http.proxyPort=3128
systemProp.https.proxyHost=192.168.0.1
systemProp.https.proxyPort=3128



Set Local Gradle
Download Link:
http://services.gradle.org/distributions/gradle-2.14.1-all.zip

$ SET "CORDOVA_ANDROID_GRADLE_DISTRIBUTION_URL=http://ionic.co.id/gradle-2.14.1-all.zip"

https://repo1.maven.org/maven2/com/android/tools/build/builder/2.2.0/builder-2.2.0.jar