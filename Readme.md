
## Installation

#### Create the Database

Take the content of databaseSetup.sql and run it to your MySql SQL section.

#### Insert connexion information

go to 'config/config.php' and change the informations by yours

## Usage

#### Run the script

In your terminal go to your project location. \
First of all you need to set up initial data to make the scrpit work. \
Run :
```
php script.php insertInitial
```
Then run :
```
php script.php createTicket, {title}, {description}, {section}
```

Your first ticket is now in the database, to get it :

```
php script.php getTicket {the title}
```

## All command Available

```
php script.php insertInitial                                        //! just one time
php script.php createTicket, {title}, {description}, {section}      //create a new ticket
php script.php getTicket, {ticket title}                            //get a ticket
php script.php getTickets                                           //get all tickets
php script.php createComment, {text}, {ticket title}                //create a new comment
php script.php getComments, {ticket title}                          //get all comments
php script.php saveTicket, {ticket title}                           //save the ticket in a file

```