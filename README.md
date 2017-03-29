# JsonApiToolsBundle
A collection of some very rough draft stuff I have for speeding up writing apis for symfony in json api standard.  Not done, and very ugly.


Installation
------------
Add with composer
```bash
composer require "tyhand/json-api-tools-bundle" "^0.1.2"
```
Add to AppKernel
```php
$bundles = array(
  // ...
  new TyHand\JsonApiToolsBundle\TyHandJsonApiToolsBundle(),
  // ...
);
```
Create Resource
---------------
For example, here is book resource
```php
<?php
// BookResource

namespace AppBundle\ApiResource;

use TyHand\JsonApiToolsBundle\Annotation\Resource;
use TyHand\JsonApiToolsBundle\Annotation\Attribute;
use TyHand\JsonApiToolsBundle\Annotation\HasOne;
use TyHand\JsonApiToolsBundle\Annotation\Filter;
use TyHand\JsonApiToolsBundle\Annotation\Validator;
use TyHand\JsonApiToolsBundle\Extra\SearchableResource;

/**
 * @Resource(entity="AppBundle\Entity\Book")
 */
class BookResource extends SearchableResource
{
    /**
     * @Attribute
     */
    public $title;
    
    /**
     * @Attribute
     */
    public $genre;
    
    /**
     * @HasOne
     */
    public $author;
    
    protected function getSearchableEntityFields()
    {
        return [
            'genre',
            'title',
            ['property' => 'author.name', 'joinType' => 'outer']
        ];
    }
}
```
Here is also an Author resource connected to the above example
```php
<?php
// Author Resource
namespace AppBundle\ApiResource;

use TyHand\JsonApiToolsBundle\Annotation\Resource;
use TyHand\JsonApiToolsBundle\Annotation\Attribute;
use TyHand\JsonApiToolsBundle\Annotation\HasMany;
use TyHand\JsonApiToolsBundle\Annotation\Filter;
use TyHand\JsonApiToolsBundle\Annotation\Validator;

use TyHand\JsonApiToolsBundle\ApiResource\JsonApiResource;
use TyHand\JsonApiToolsBundle\ApiResource\Resource as ApiResource;

/**
 * @Resource(entity="AppBundle\Entity\Author")
 */
class AuthorResource extends ApiResource
{
    /**
     * @Attribute
     */
    public $name;

    /**
     * @HasMany
     */
    public $books;
}
```

Tag Resource
------------
Tag the resources as services
```yaml
# services.yml

services:
    author_resource:
        class: AppBundle\ApiResource\AuthorResource
        tags:
            - { name: jsonapi_tools.resource }
    book_resource:
        class: AppBundle\ApiResource\BookResource
        tags:
            - { name: jsonapi_tools.resource }
```
Create Controllers
------------------
Just inherit the resources controller for quick setup
```php
<?php
// BookController

namespace AppBundle\Controller;

use JsonApiBundle\Controller\ResourceController;

class BookController extends ResourceController
{
}
```

Routing
-------
```yaml
# routing.yml

jsonapi_author:
    resource: AppBundle\Controller\AuthorController
    type:     jsonapi_resource

jsonapi_book:
    resource: AppBundle\Controller\BookController
    type:     jsonapi_resource
```

If all is well, then this stuff should work.  Real documentation incoming soon.




      
