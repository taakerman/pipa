Pipa
============

The Pipa (PHP Image Processing Algorithms) library provides PHP 
implementations of some standard image algorithms. 

# Performance notes
PHP has some limitations w.r.t. performance, but nonetheless sometimes these 
algorithms are needed in PHP for simple use cases.

The library is optimized for readability, performance is second to that. There 
are some things you can do yourself, to speed up things when using this library:
*   If using GD, Imagick or any other image framework, check if the framework 
    has a native implementation of the algorithm you want to use. (it is 
    compiled and thus faster)
*   If you need to do multiple things to an image and scaling is one of them, 
    then scale first

However if you feel like it, you can always optimize the algorithms yourself, by 
re-implementing them using this library as a reference. Notes to enhance 
performance in PHP and this library:
*   Inline all functions
*   Use ++$i not $i++
*   Cache heavy calculations (utilizing memory instead of CPU cycles)
*   Try to find articles online that explains the math behind the algorithms 
    and faster ways to implement the algorithms
*   Looping the image is expensive. If you need to do several calculations, 
    then consider if you can do them in the same loop (e.g. grayscale and 
    calculate mean and variance).
*   If only dealing with grayscale or monochrome (binary), then several algorithms can 
    be heavily optimized (from 3 or 4 channels to 1)

If you need to do extensive image processing, then it would be advisable to use 
an alternative language, like C++ in combination with OpenCV, which can utilize 
several processors in multiple threads, including the Graphics Processor which 
is very fast for many image algorithms.

There are some bindings of OpenCV and PHP, but it requires compilation into PHP 
which not all have the ability to do (eg if you are on a shared host and do not 
have a dedicated server/PHP installation).

https://github.com/mgdm/OpenCV-for-PHP

# TODO
performance tests
examples
complete histogram hash
complete test cases


# Resources

## Other implementations
* Intervention Image 

## Quantization


## Normalization:
* http://en.wikipedia.org/wiki/Normalization_(image_processing)
* http://en.wikipedia.org/wiki/Normalization_(statistics)
* http://www.griaulebiometrics.com/en-us/book/understanding-biometrics/types/enhancement/based
* http://stackoverflow.com/questions/18576538/image-normalization-in-java

## Equalization
* http://en.wikipedia.org/wiki/Histogram_equalization
* http://www.tutorialspoint.com/dip/Histogram_Equalization.htm
* http://poseidon.csd.auth.gr/papers/PUBLISHED/JOURNAL/pdf/Bassiou07a.pdf

## Matching
* http://en.wikipedia.org/wiki/Histogram_matching


## Hamming Distance
* http://en.wikipedia.org/wiki/Hamming_distance


## Image Similarity
* http://hackerlabs.org/blog/2012/07/30/organizing-photos-with-duplicate-and-similarity-checking/
* http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html
* http://staff.science.uva.nl/~rein/UvAwiki/uploads/CV0708/swainballard.pdf
* http://ceur-ws.org/Vol-547/60.pdf (quantize hist, make smaller, hash, then use hash)


## Image Scaling
* http://blog.codinghorror.com/better-image-resizing/
* http://willperone.net/Code/codescaling.php
* http://www.compuphase.com/graphic/scale2.htm
* http://tech-algorithm.com/articles/nearest-neighbor-image-scaling/

## Color Models
* http://www.ijcta.com/documents/volumes/vol5issue2/ijcta2014050210.pdf


## PHP optimization
* http://www.tuxradar.com/practicalphp/18/0/0
* https://developers.google.com/speed/articles/optimizing-php
*http://www.mdproductions.ca/guides/50-best-practices-to-optimize-php-code-performance

