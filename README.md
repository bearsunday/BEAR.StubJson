# BEAR.StubJson
### Stubbing with JSON Mappings for BEAR.Resource

Before coding with actual DB access by preparing stubs...

* You can develop front-end HTML templates.
* You can check the final output.
* You can develop the backend in parallel.
* JSON values can be in a common language.

スタブを用意することによって実際のDBアクセスを伴うコーディングを行う前に

 * フロントエンドのHTMLテンプレートの開発ができます。
 * 最終出力の確認ができます。
 * バックエンドの平行開発ができます。
 * JSONの値を共通言語として使えます。

## Composer install

    composer require bear/stub-json

## Module install

```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\AuraSqlQueryModule;

class StubModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new StubJsonModule('path/to/stubJson'));
    }
}
```

## Stub JSON

Instead of the ResourceObject method being invoked, the stub's JSON value is set to the body of the resource.

ResourceObjectメソッドが呼び出される代わりに、スタブのJSON値がリソースのボディに設定されます。

`var/stubJson/Page/Index.json`

```json
{
    "foo": "foo1"
}
```
