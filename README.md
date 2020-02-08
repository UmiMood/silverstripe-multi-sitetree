Multi Site Tree 
---
[![Build Status](https://travis-ci.com/UmiMood/silverstripe-multi-sitetree.svg?token=4CfuxCuzvs5i12ZugsAZ)](https://travis-ci.com/UmiMood/silverstripe-multi-sitetree)

Module provides ability to create multiple site tree sections to divide content pages based on class type.

## Requirement
1. silverstripe/cms ^4.3
2. php ^7

## Installation
`composer require umimood/multi-sitetree`

## TODO

1. Better code coverage
2. Ability to filter by namespace

## Usage

### 1. Extend the base classes

1. CMSPagesController
2. CMSPageAddController
3. CMSPageEditController
4. CMSPageHistoryViewerController
5. CMSPageSettingsController

```php
class CMSEmailsController extends \UmiMood\MultiSiteTree\Controllers\CMSPagesController
{
}
```

```php
class CMSEmailAddController extends \UmiMood\MultiSiteTree\Controllers\CMSPageAddController
{
}
```

```php
class CMSEmailEditController extends \UmiMood\MultiSiteTree\Controllers\CMSPageEditController
{
}
```

```php
class CMSEmailHistoryViewerController extends \UmiMood\MultiSiteTree\Controllers\CMSPageHistoryViewerController
{
}
```

```php
class CMSEmailSettingsController extends \UmiMood\MultiSiteTree\Controllers\CMSPageSettingsController
{
}
```

### 2. Define configuration in config.yml

```yaml
# Multi site tree section configuration
App\CMSEmailsController:
  family_controller: App\CMSEmailsController
  menu_priority: 26
  url_segment: emails
  menu_title: Emails
  classes:
    - App\EmailEvent
    - Some\Other\EmailEvent
    - And\Another\EmailEvent

App\CMSEmailAddController:
  family_controller: App\CMSEmailsController
  url_segment: emails/add
  menu_title: Emails

App\CMSEmailEditController:
  family_controller: App\CMSEmailsController
  url_segment: emails/edit
  menu_title: Emails

App\CMSEmailHistoryViewerController:
  family_controller: App\CMSEmailsController
  url_segment: emails/history
  menu_title: Emails

App\CMSEmailSettingsController:
  family_controller: App\CMSEmailsController
  url_segment: emails/settings
  menu_title: Emails
```

Note: Sub class of `UmiMood\MultiSiteTree\Controllers\CMSPagesController` is parent controller (identifier) and needs to be assigned to all five (5) controller classes. 

## Maintainer
Umair Mahmood <umair.mahmood22@gmail.com.au>

## Bugtracker
Bugs are tracked in the issues section of this repository. Before submitting an issue please read over 
existing issues to ensure yours is unique. 
 
If the issue does look like a new bug:
 
 - Create a new issue
 - Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots 
 and screencasts can help here.
 - Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version, 
 Operating System, any installed SilverStripe modules.
 
Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.
 
## Development and contribution
If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.

