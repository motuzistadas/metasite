## Installation

1) Install and run the project:

```sh
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install
php app/console doctrine:schema:create
```

2) To use the login system, you need to get your client ID and secret on at least one provider:

| Provider       | Setup URL                                     |
| -------------- | --------------------------------------------- |
| GitHub         | https://github.com/settings/developers        |
| StackExchange  | https://stackapps.com/apps/oauth/             |
| Google         | https://console.developers.google.com/project |
| Twitter        | https://apps.twitter.com/                     |
| Facebook       | https://developers.facebook.com/apps/         | 

3) To see all administration content you have to login (at this example throw Facebook)! Once you logged in, you can run the following commands:

```sh
# list your users, from here you can find your id (should be 3, but stay safe).
php app/console user:list

# enable user with id = 3
php app/console user:enable 3

# set user with id = 3 as admin
php app/console user:admin 3
```

## File for subscribers

File for saving subscribers info saved in web/subscriptions.csv





