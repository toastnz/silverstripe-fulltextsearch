# SilverStripe Blocks 

Simple content blocks system. Nothing fancy, easy to implement.

## Requirements

See composer.json

## Installation

Add the following to your `config.yml` (optional):

```yaml
PageController:
  extensions:
    - Toast\Blocks\Extensions\PageControllerExtension
```

Use `Page` or other class that extends `SiteTree`.

In your `Layout/Page.ss` template, add the following:

```silverstripe
<% loop $ContentBlocks %>
    $ForTemplate
<% end_loop %>
```

## Configuration

### Add / remove available block classes

```yaml
Toast\Blocks\Extensions\PageExtension
  available_blocks:
    - Toast\Blocks\TextBlock
```

### Create a custom block

Extend `Block` to create a new block type.

```php
<?php
 

class MyBlock extends Toast\Blocks\Block
{
    private static $singular_name = 'My Block';
    private static $plural_name = 'My Blocks';
    private static $icon = 'mysite/images/blocks/custom.png';
    
    private static $db = [
        'Content' => 'HTMLText'
    ];

}
```

`/themes/default/templates/Toast/Blocks/MyBlock.ss`:

```silverstripe
<%-- Your block template here --%>

<h2>$Title</h2>
$Content
```

## Todo:

* Template global providers
* Zoning
* Duplicate handlers
* Tests
* `Through` tables for more advanced versioning
