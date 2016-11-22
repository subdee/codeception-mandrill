[![Latest Stable Version](https://img.shields.io/packagist/v/subdee/codeception-mandrill.svg)](https://packagist.org/packages/subdee/codeception-mandrill) 

# Codeception Mandrill Module

This package provides a Mandrill moule for Codeception. 

## Installation

Add it to composer.json

```bash
    composer require --dev subdee/codeception-mandrill
```

### Configuration

```yml
modules:
    enabled: [AcceptanceHelper, Db, Mandrill]
    config:
        Mandrill:
            api_key: ADD_YOUR_MANDRILL_TEST_API_KEY
 ```     
 
Update Codeception build
  
  ```bash
  codecept build
  ```

Done