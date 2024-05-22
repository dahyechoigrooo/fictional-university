## How to release your local WP site to live WP site
### 1. Create Domain 
- Create a domain on the dreamhost.
- Set your admin account password. (Admin > Users > Profile)

### 2. Migrate Live WP Site
- Install Plugin "All-in-One WP Migration and Backup" on your local WP site
- Export your local WP site database.
- Delete files that are too heavy like "node-modules" on your local WP site.
- Install Plugin "All-in-One WP Migration and Backup" on your live WP site.
- Import the database exported from your local WP site to the live WP site.

### 3. Get Started With Git
- Install the git
- ```git --version```
- ```git config --global user.name ":USERNAME"```
- ```git config --global user.email ":YOUREMAIL"```
- ```cd wp-content```
- ```git init```
- create file '.gitignore'
- ```git add .```
- ```git commit -m "commit message"```
- If you want to return to a committed history. ```git checkout -- .```

### 4. How to login into your live site With SSH
- access DreamHost 
- click the menu Manage Website and click your live WP site. 
- enable the secure shell Access(SSH).
- reset the password.
- ```ssh dh_7urqie@fictional.saichoiblog.com```
  - ```yes```
  - ```:password```
  - make directory for git push ```mkdir gitrepo``` 
  - ```git init --bare```
  - make directory inside the hooks. ```cd hooks``` and ```touch post-receive```
  - ```nano post-receive```
  - ```
    #!/bin/bash
    git --work-tree=/home/dh_7urqie/fictional.saichoiblog.com/wp-content --git-dir=/home/dh_7urqie/gitrepo checkout -f
    ```
  - ctrl + x
  - Enter the key 'Y'.
  - Enter.
- Folder permission  ```chmod +x post-receive```
- Open your local WP site and move folder to wp-content. 
  - ```git remote add live ssh://dh_7urqie@fictional.saichoiblog.com/home/dh_7urqie/gitrepo```
  - ```git checkout -b master```
  - ```git add -A```
  - ```git commit -m "commit message```
  - ```git push live master```
  - ```git remote set-url live ssh://dh_7urqie@fictional.saichoiblog.com/home/dh_7urqie/gitrepo```
  - Enter the password
- If you want to install Plugins on live site ```cd fictional.saichoiblog.com``` and ```wp plugin install query-monitor --activate```

## How to git push changing the code
1. change the code.
2. ```git add -A```
3. ```git commit -m "commit message"```
4. ```git push live master```

## Passwordless SSH Login
- ```cd ~```
- ```cd .ssh```
- ```cat id_rsa.pub ``` copy the result in clipboard.
  - If you don't have ssh key then you can create.
    - cd ~
    - ssh-keygen -t ed25519 -C "youremail@google.com"
    - Enter the key 'y',
    - Enter.
- access the SSH ```ssh://dh_7urqie@fictional.saichoiblog.com```
- Enter your password.
- ```pwd```
- ```ls -a```
- ```mkdir .ssh```
- ```ls -a```
- ```cd .ssh```
- ```touch authorized_keys```
- ```nano authorized_keys```
- Paste the text on the clipboard.
- ctrl + x
- Enter the key 'y'
- Enter
- ```cd ~```
- ```chmod 700 .ssh```
- ```chmod 600 .ssh/authorized_keys```

## How to use SVG icon on admin menu
1. Open your svg file.
2. Copy the svg text.
3. ```btoa(`Paste svg text here`)``` and copy the results for this command line.
4. Use the clipboard value as the icon_url value.

## How to use JSX
1. Install node.js on 'nodejs.org'(npm is also installed)
2. ```node --version```
3. Install @wordpress/scripts
4. ````npm init -y```` (The package.json file is created)
5. ```npm install @wordpress/scripts --save-dev``` (The node_modules folder is created)
6. Create the src folder and create the index.js file in it.
7. Add code the package.json file.
```agsl
// package.json 

"scripts": {
  "build": "wp-scripts build",
  "start": "wp-scripts start",
```
8. Command line ```npm run start```(The build folder is created)
