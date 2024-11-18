## About Laravel Assessment Keith Msimango

To Execute a job from script directly in the project source code

Navigate to the Following Folder: 
App -> AssessmentIncludes folder 

_to run the code Please follow the following script methods_ 
<br>
`php BackgroundScript.php classname method name params`

<br>
Example command line execution in the same directory <b><u>App/AssessmentIncludes</u></b> :


`php BackgroundScript.php BackgroundScript.php App\\Jobs\\ExampleRunBackgroundJob1 create 'sam','has','breakfast'`

or
Example of Real test Scenario

`php app/AssessmentIncludes/BackgroundScript.php counter startCounter 10`

This should give you a response of success or failure in the command, whether your job has been triggered to be ran. 

___



