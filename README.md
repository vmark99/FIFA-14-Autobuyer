FIFA-14-Autobuyer
=================
## The idea
The idea of this Autobuyer is that everything that get's contributed, will be checked and pushed to the opensource autobuyer. I will host the website and make sure the submitted code is safe. Neither the contributor nor am I responsible in case your EA credentials are abused or obtained. The more contributors, the better. Everything will be completely free and I'm aiming to make it as user friendly as humanly possible. This Autobuyer cannot be used for commercial purposes. It is ok to download it and install it on your own web host but selling licenses for it will certainly be not allowed and punished according to the Attribution-NonCommercial-ShareAlike 3.0 Unported License.

Suggestions are always welcome! Donations will go towards the web hosting so it can be accessed and used by all of you.

I'm doing this because the average AB is starting to cost over 100 euro's and the effectivity is strongly starting to decrease. 

## To-do
- Web design
- Finishing the EA Connection Class
- Starting on the EA search class
- Choosing a safe and up-to date framework (Suggestions!)
- Deciding what will be Javascript and what will be done server side


### Initialization
```php
require 'classes/connector.php'
$con = new Connector($email, $password, $answer, $system);
$connection = $con->connect();
```
This will return the cookies for the use of the functions.
