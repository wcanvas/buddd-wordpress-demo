# White Canvas Boilerplate Documentation

## Boilerplate
  This is a boilerplate starter theme for White Canvas Wordpress FSE projects. It is not meant to be used as is, but as a starting point for custom themes for each client.

  - Blocks starting point and examples - ACF fields, templates, template-parts, Tailwind CSS, SASS and animations.
  - Components.
  - Commonly needed third party scripts - [Gsap](https://greensock.com/gsap/), [Swiper](https://swiperjs.com/), [Axios](https://axios-http.com/docs/intro).
  - Initial setup for Tailwind CSS, JS and PHP.
  - PSR-4 file standard for PHP.
  - JS and SCSS Linters.

### Requirements

  - nodejs: 20.11.0
  - npm: 10.2.4
  - php: 8.2.10
  - composer: 2.6.4

### Plugins in use

  - [Advanced Custom Fields PRO (REQUIRED)](https://www.advancedcustomfields.com/)
  - [Yoast SEO](https://yoast.com/)
  - [Gravity Forms](https://www.gravityforms.com/)
  - [EWWW Image Optimizer](https://ewww.io/)
  - [Safe SVG](https://wordpress.org/plugins/safe-svg/)
  - [Wordfence Security](https://www.wordfence.com/)
  - [Activity Log](https://wordpress.org/plugins/aryo-activity-log/)
  - [WPS Hide Login](https://wordpress.org/plugins/wps-hide-login/)

### Boilerplate Development Setup (Manual)

  1.  ​Start up a [LocalWP](https://localwp.com/) project with the default config.
  2.  Go to the site folder/app and delete the `public` folder
  3.  Clone the repository on the `app` folder and rename the repository folder as `public`.
  4.  Copy the content of `wp-config-sample.php` into a brand new `wp-config.php` file
  5.  Change the database name (local), user (root), and password (root).
  6.  (INITIAL REPO SETUP ONLY) Go to the theme folder and run the `npm run setup` command to rename the theme and setup the PSR-4 autoloader.
  7.  On the theme folder run `npm install` and `composer install` in order to install all the dependencies and setup PSR-4.
  8.  Create a `.env` file on the theme's root and copy the contents of the `.env-webpack` file inside, then change the `LOCAL_SERVER_URL` variable inside the `.env` file to point to you LocalWP Site Domain.
  9.  Run `npm run dev` to start coding, this should open your site with browser sync (that allow you to work with auto refresh each time you save some file).
  10. Activate all plugins.
  11. Activate your theme.
  12. Go into Custom Fields and sync all fields.
  13. The setup is done and you can start building your theme!

### Docker Environment (Optional)

  To simplify the process of install the required packages we have already build an docker image with all packages already installed.

  To setup your docker environment follow those steps:

  1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop/)
  2. Install [Dev Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) VS-Code extension.
  3. Create an `docker-compose.yml` file on root level to setup your environment just like this example:
     ```
     version: "3.9"
     services:
       builder:
         image: whitecanvas/builder-v8:1.0.6
         volumes:
           - ./:/media/
         extra_hosts:
           - "boilerplate.local:192.168.1.6"
         command:
           - sh
         tty: true
     ```
     - Replace `boilerplate.local` with the virtualhost that LocalWP created.
     - Replace `192.168.1.6` with your machine ip, you can get it running this command `ifconfig | grep "inet " | grep -v 127.0.0.1 | awk '{print $2}'`
  4. Start your container by running `docker-compose up -d`, this command will pull the image the first time that you use it and then it will run your container in background (you can check it in Docker Desktop app, the cli or with Dev Containers extension)
  5. Open the Remote Explorer icon on the sidebar of VS-Code, look for your container and click on Attach to container.
  6. Open the `/media/` directory in your VS-Code new container window and have fun!
  7. To turn off and destroy your container run `docker-compose down`.

  Note that you can use the image `builder` as well in MACOS but it works slow vs `builder-v8`.

### Boilerplate Documentation

#### Theme Boilerplate Structure

```bash
  .wcanvas-boilerplate/
  ├── acf-json
  │   ├── group_649ae283171a4.json
  │   ├── ...
  │   ├── group_649c99ba6d6e0.json
  │   └── ui_options_page_651edea3d58cb.json
  ├── assets
  │   ├── js
  │   │   ├── lib
  │   │   │   ├── animations
  │   │   │   │   ├── fades
  │   │   │   │   │   ├── fade-in-up.js
  │   │   │   │   │   ├── reveal-from-columns.js
  │   │   │   │   │   └── reveal.js
  │   │   │   │   ├── parallax
  │   │   │   │   │   ├── horizontal-paralllax.js
  │   │   │   │   │   ├── vertical-parallax-rotation.js
  │   │   │   │   │   └── vertical-parallax.js
  │   │   │   │   ├── text
  │   │   │   │   │   ├── line-by-line.js
  │   │   │   │   │   ├── word-by-word-bottom-to-top.js
  │   │   │   │   │   └── word-by-word.js
  │   │   │   │   └── props.js
  │   │   │   ├── archive
  │   │   │   │   ├── archive.js
  │   │   │   │   ├── filters.js
  │   │   │   │   └── pagination.js
  │   │   │   ├── accordion.js
  │   │   │   ├── scrollManager.js
  │   │   │   ├── simple-pagination.js
  │   │   │   └── video-support.js
  │   │   ├── utils
  │   │   │   ├── api.js
  │   │   │   ├── blocks.js
  │   │   │   └── urlUtils.js
  │   │   ├── admin.js
  │   │   ├── index.js
  │   │   └── resizable-sidebar.js
  │   ├── media
  │   │   ├── icons
  │   │   │   ├── arrow-dropdown.svg
  │   │   │   ├── arrow-green-menu.svg
  │   │   │   └── youtube.svg
  │   │   └── images
  │   │       ├── cards-placeholder.png
  │   │       └── logo-placeholder.png
  │   └── styles
  │       ├── admin
  │       │   ├── _all.css
  │       │   ├── _gutenberg.css
  │       │   └── _login.css
  │       ├── editor
  │       │   ├── _all.css
  │       │   ├── _editor-layout.css
  │       │   └── _editor-reset.css
  │       ├── utilities
  │       │   ├── _all.css
  │       │   ├── _animations.css
  │       │   ├── _cards.css
  │       │   ├── _containers.css
  │       │   ├── _core_blocks.css
  │       │   ├── _reset.css
  │       │   ├── _typography.css
  │       │   └── _video.css
  │       ├── admin.css
  │       ├── editor.css
  │       ├── index.css
  │       └── resizable-sidebar.scss
  ├── blocks
  │   ├── accordion-v1
  │   │   ├── block.json
  │   │   ├── edit.js
  │   │   ├── editor.scss
  │   │   ├── index.js
  │   │   ├── render.php
  │   │   ├── style.scss
  │   │   └── view.js
  │   ├── archive
  │   │   ├── block.json
  │   │   ├── edit.js
  │   │   ├── editor.scss
  │   │   ├── index.js
  │   │   ├── render.php
  │   │   ├── style.scss
  │   │   └── view.js
  │   └── tabber
  │       ├── block.json
  │       ├── edit.js
  │       ├── editor.scss
  │       ├── index.js
  │       ├── render.php
  │       ├── style.scss
  │       └── view.js
  ├── components
  │   ├── accordion-item
  │   │   └── accordion-item.php
  │   ├── archive-error
  │   │   ├── archive-error.php
  │   │   ├── editor.scss
  │   │   └── style.scss
  │   └── tabber-tab
  │       └── tabber-tab.php
  ├── dev-scripts
  │   ├── block_template
  │   │   ├── templates
  │   │   │   └── block
  │   │   │       ├── edit.js.mustache
  │   │   │       ├── ...
  │   │   │       ├── render.php.mustache
  │   │   │       └── view.js.mustache
  │   │   └── index.js
  │   ├── component_template
  │   │   ├── editor.scss.mustache
  │   │   ├── index.js
  │   │   ├── style.scss.mustache
  │   │   └── template.php.mustache
  │   ├── build_modules.sh
  │   ├── dependencies.js
  │   ├── dependencies.json
  │   └── rename-theme.js
  ├── parts
  │   ├── footer.html
  │   └── header.html
  ├── src
  │   ├── Block
  │   │   ├── Blocks.php
  │   │   ├── BlockWrapper.php
  │   │   └── CoreBlocks.php
  │   ├── Functionalities
  │   │   ├── Archive
  │   │   │   ├── Archive.php
  │   │   │   ├── ArchiveApi.php
  │   │   │   └── ArchiveQuery.php
  │   │   ├── BlockPlaceholder.php
  │   │   ├── Component.php
  │   │   ├── ...
  │   │   ├── SEO.php
  │   │   └── WebpImages.php
  │   ├── Plugins
  │   │   ├── AcfSettings.php
  │   │   └── GravityFormsSettings.php
  │   └── Setup
  │       ├── AssetsDependencies.php
  │       ├── DisableComments.php
  │       ├── ImageHandler.php
  │       ├── PerformanceSettings.php
  │       ├── Security.php
  │       └── ThemeSettings.php
  ├── templates
  │   ├── 404.html
  │   ├── index.html
  │   └── page.html
  ├── composer.json
  ├── functions.php
  ├── package.json
  ├── phpcs.xml
  ├── phpmd.ruleset.xml
  ├── style.css
  ├── tailwind.config.js
  ├── theme.json
  └── webpack.config.js
```
#### Working with the Theme Boilerplate

  The theme boilerplate is a Wordpress Theme using the Full Site Editing (FSE) functionality from Wordpress. So most of the work will be done on the ACF blocks, ACF fields and Components inside the /blocks, /components and /src folders.

##### Creating a new block

  To create a new block, you can use the `npm run block -- [block-name]` command in the theme's directory. This will create a new block with the name you provided and a default structure. Once the command finishes you will see a folder with the name of the block inside the `/blocks` folder.

  To learn more about Blocks and ACF Blocks you can check the [WordPress Documentation](https://developer.wordpress.org/block-editor/) and the [ACF Documentation](https://www.advancedcustomfields.com/resources/blocks/).

##### Creating a new component

  Components are a combination of php and scss files intended to be used inside multiple blocks.

  To create a new component, you can use the `npm run component -- [component-name]` command in the theme's directory. This will create a new component with the name you provided and a default structure. Once the command finishes you will see a folder with the name of the component inside the `/components` folder.

  We are using the wp-scripts library to create and build blocks, scss and javascript files.

##### PHP Functionality and Theme Setup

  For more complex php functionality and theme wide setup you can create PHP classes on the `/src` folder. This classes will be autoloaded by composer.

  - `/src/Blocks` - Classes for blocks specific functionality.
  - `/src/Functionalities` - Classes for theme wide functionality.
  - `/src/Plugins` - Classes for third party plugins.
  - `/src/Setup` - Classes for theme setup.

##### Assets Folder

  The assets folder is the folder where all the assets for the theme are stored. It is divided into four main folders:

  - `/assets/js` - In the js folder we can find functionality scripts and libraries to be used across the theme.
  - `/assets/styles` - In the styles folder we can find the styles for the front and the editor view of the theme. We are also using a custom reset.css file and some custom styles for core blocks.
  - `/assets/media` - Media assets like icons, images, etc.
  - `/assets/build` - Build files generated by the custom webpack configuration coming from the wp-scripts library and extended by the `webpack.config.js` file.


##### Dev Scripts folder

  The dev scripts folder is a folder where we can find some of the scripts that we can use to develop the theme, for example:

  - `/dev-scripts/block_template` - A script to create a new block template.
  - `/dev-scripts/component_template` - A script to create a new component template.
  - `/dev-scripts/rename-theme` - A script to rename the theme prefixes.

##### Theme Styling, theme.json and Tailwind CSS

  We are using Tailwind CSS for the styles. The configuration file is on the root of the theme called `tailwind.config.js`. The main idea is to read some of the variable from the `theme.json` file and use them on the `tailwind.config.js` file. For example: colors, font-family, font-size, etc.

##### Linters and Fixers

###### Linters

  We are using linters for all file types. These linters should run automatically on CI/CD pipelines, but you can also run them locally with the commands below.

  - PHP Code Sniffer: `composer run check-cs`
  - ESLint: `npm run lint:js`
  - Stylelint: `npm run lint:css`

###### Fixers

  The fixers will try to fix the issues automatically.

  - PHP Code Sniffer: `composer run fix-cs`
  - ESLint: `npm run fix:js`
  - Stylelint: `npm run fix:css`

  The linters and fixers for PHP are provided by the PHPCS library and run with composer. The linters and fixers for JS and CSS are provided by the wp-scripts library and run with npm.

  For the linters to work and show errors on your VSCode based ide, you need to install the following extensions:

  - PHP Code Sniffer: `phpcs`
  - ESLint: `eslint`
  - Stylelint: `stylelint`

##### WordPress Coding Standards

  Some of the coding standards and good practices are:

  - Use the theme Prefixes for all the classes.
  - Use the theme Prefixes for php variables, actions, filters, etc.
  - Use the theme Prefixes for the tailwind classes and BEM notation for scss.

  Most of these coding standards are already enforced by the phpcs library linters.

#### Repository

##### Pipelines

  We use manual actions pipelines, that allow us to deploy stages from gitlab pull requests.
  Also we have [docker images](https://hub.docker.com/repositories/whitecanvas) created to build our app.

  Pipeline file: `.gitlab-ci.yml`

  We also hide some files from the server to avoid exposing sensitive information with the `.hide_dev_files` file. This file is a list of files that will be hidden from the final push to a WP Engine server. It only accepts file names and directories separated by a new line.

