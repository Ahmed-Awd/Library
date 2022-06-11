set arg1=%1
git pull origin main
git add .
git commit -m %arg1%
git push origin main
