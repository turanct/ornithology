Ornithology CLI Twitter client
========================================

A very basic command line twitter client, written in PHP


1. Goals & Accomplishments
----------------------------------------

### 1.1 Goals

- Use proven libraries like the Symfony Console Component and ZendService\Twitter
- Be faster than ttytter (which takes ages for a refresh command)
- Have a structured, extensible codebase


2. Getting started
----------------------------------------

### 2.1 Requirements

- php 5.3


### 2.2 Installation

Use this command to install Ornithology on your system:

	git clone https://github.com/turanct/ornithology.git /usr/local/ornithology && cd /usr/local/ornithology && curl -sS https://getcomposer.org/installer | php && php composer.phar install && ln -s /usr/local/ornithology/ornithology.php /usr/local/bin/ornithology && cd -


### 2.3 Usage

#### 2.3.1 Prepare ornithology config directory

create a directory for ornithology configs

	mkdir ~/.ornithology

#### 2.3.2 Register your application with twitter

1. Go to https://dev.twitter.com and login with your twitter username and password
2. Click your avatar and navigate to My Applications
3. Create a new application profile for Ornithology (leave the Callback URL field empty).
4. Navigate to the API Keys menu in your app's profile, and generate a new API key. The app needs Read, Write & Direct Message permissions. You'll need this API key and API secret in the next step.
5. Create a file called `Consumer.php` in your `~/.ornithology` directory, and put the following content in there:

```php
<?php

return array(
    'consumerKey' => 'YOUR-API-KEY-HERE',
    'consumerSecret' => 'YOUR-API-SECRET-HERE',
);
```

Don't forget to replace the placeholders in that piece of code by your actual key and secret.

#### 2.3.3 Run ornithology for the first time!

In your terminal:

	ornithology

You should now see something like "Welcome to the Ornithology shell", and the shell line, something like this: `Ornithology > `

The last thing we need to do before we can use the application, is allow the application access to our twitter profile. Type this: `authorize`. Your browser will open and you'll be redirected to a Twitter page asking you to authorize this application. Type the pin-code that Twitter gives you into the application, and you're all set.

#### 2.3.4 Using ornithology when you're already authenticated

In your terminal:

	ornithology

- You can then start typing commands, for example `refresh` to get a list of unread tweets, or `tweet` to compose a new tweet.
- For a complete list of commands, type `list` or `help`.
- To quit the application, type `quit`.


### 3. License

Gunpowder is licensed under the *MIT License*
