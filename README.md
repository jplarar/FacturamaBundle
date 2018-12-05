# JplararFacturamaBundle
A simple Symfony2 bundle for the API for AWS Facturama.

## Setup

### Step 1: Download JplararFacturamaBundle using composer

Add Facturama Bundle in your composer.json:

```js
{
    "require": {
        "jplarar/facturama-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update "jplarar/facturama-bundle"
```


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Jplarar\FacturamaBundle\JplararFacturamaBundle()
    );
}
```

### Step 3: Add configuration

``` yml
# app/config/config.yml
jplarar_facturama:
      facturama_username:    %facturama_username%
      facturama_password:    %facturama_password%
      serie:                 %serie%
      currency:              %currency%
      expedition_place:      %expedition_place%
      cfdi_use:              %cfdi_use%
      payment_form:          %payment_form%
      unit_code:             %unit_code%
      unitCode:              %unitCode%
      taxes:                 %taxes%
      env:                   %env%
```

## Usage

**Using service**

``` php
<?php
        $facturamaClient = $this->get('facturama_client');
?>
```

##Example

###Upload new file to Facturama
``` php
<?php 
    $facturamaClient->write($key, $content, $mimeType);
?>
```
