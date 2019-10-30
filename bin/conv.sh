#!/bin/bash

for i in `ls|grep .*.png`
do
  convert $i -alpha extract -background "#ffe6de" -alpha shape $i
done
