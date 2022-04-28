set arg1=%1
git pull origin develop
git add resources app database config routes Envoy.blade.php
git commit -m %arg1%
git push origin develop
