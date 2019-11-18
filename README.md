# UPPM Git Client
```bash
php uppm install gitclient
```

```php
$git = new Git();

$git->changeDirectory(".");
$git->initIfNot();
$git->setRemote("origin");
$git->add(".");
$git->commit("Hello, this push has been sent by GitPHPClient by UPPM");
$git->push("master");
```