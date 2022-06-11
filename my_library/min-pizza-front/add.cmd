set arg1=%1
git pull origin develop
git add admin
git add web
git commit -m %arg1%
git push origin develop
