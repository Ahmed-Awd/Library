set arg1=%1
set arg2=%2
git pull origin %arg2%
git add resources app database config routes Envoy.blade.php
git commit -m %arg1%
git pull origin %arg2%
git push origin %arg2%
