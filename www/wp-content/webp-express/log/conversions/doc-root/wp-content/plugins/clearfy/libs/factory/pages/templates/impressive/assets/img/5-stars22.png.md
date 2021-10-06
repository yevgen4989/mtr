WebP Express 0.20.1. Conversion triggered with the conversion script (wod/webp-on-demand.php), 2021-09-24 11:37:32

*WebP Convert 2.6.0*  ignited.
- PHP version: 7.4.23
- Server software: nginx/1.20.1

Stack converter ignited
Destination folder does not exist. Creating folder: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp
- log-call-arguments: true
- converters: (array of 10 items)

The following options have not been explicitly set, so using the following defaults:
- auto-limit: true
- converter-options: (empty array)
- shuffle: false
- preferred-converters: (empty array)
- extra-converters: (empty array)

The following options were supplied and are passed on to the converters in the stack:
- alpha-quality: 60
- encoding: "auto"
- metadata: "none"
- near-lossless: 60
- quality: 65
------------


*Trying: cwebp* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp
- alpha-quality: 60
- encoding: "auto"
- low-memory: true
- log-call-arguments: true
- metadata: "none"
- method: 6
- near-lossless: 60
- quality: 65
- use-nice: true
- try-common-system-paths: true
- try-supplied-binary-for-os: true
- command-line-options: ""

The following options have not been explicitly set, so using the following defaults:
- auto-limit: true
- auto-filter: false
- default-quality: 85
- max-quality: 85
- preset: "none"
- size-in-percentage: null (not set)
- sharp-yuv: true
- skip: false
- try-cwebp: true
- try-discovering-cwebp: true
- rel-path-to-precompiled-binaries: *****
- skip-these-precompiled-binaries: ""
------------

Encoding is set to auto - converting to both lossless and lossy and selecting the smallest file

Converting to lossy
Looking for cwebp binaries.
Discovering if a plain cwebp call works (to skip this step, disable the "try-cwebp" option)
- Executing: cwebp -version 2>&1. Result: *Exec failed* (the cwebp binary was not found at path: cwebp, or it had missing library dependencies)
Nope a plain cwebp call does not work (spent 4 ms)
Discovering binaries using "which -a cwebp" command. (to skip this step, disable the "try-discovering-cwebp" option)
Found 0 binaries (spent 12 ms)
Discovering binaries by peeking in common system paths (to skip this step, disable the "try-common-system-paths" option)
Found 0 binaries (spent 0 ms)
Discovering binaries which are distributed with the webp-convert library (to skip this step, disable the "try-supplied-binary-for-os" option)
Checking if we have a supplied precompiled binary for your OS (Linux)... We do. We in fact have 4
Found 4 binaries (spent 0 ms)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-103-linux-x86-64-static
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-061-linux-x86-64
Discovering cwebp binaries took: 16 ms

Binaries ordered by version number.
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64: (version: 1.2.0)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64: (version: 1.1.0)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-103-linux-x86-64-static: (version: 1.0.3)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-061-linux-x86-64: (version: 0.6.1)
Starting conversion, using the first of these. If that should fail, the next will be tried and so on.
Checking checksum for supplied binary: [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64
Checksum test took: 38 ms
Creating command line options for version: 1.2.0
Bypassing auto-limit (it is only active for jpegs)
Quality: 65. 
The near-lossless option ignored for lossy
Trying to convert by executing the following command:
nice [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64 -metadata none -q 65 -alpha_q '60' -sharp_yuv -m 6 -low_memory '[doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png' -o '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp.lossy.webp' 2>&1

*Output:* 
[doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64: /lib/x86_64-linux-gnu/libm.so.6: version `GLIBC_2.29' not found (required by [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64)

Executing cwebp binary took: 5 ms

Exec failed (return code: 1)
Note: You can prevent trying this precompiled binary, by setting the "skip-these-precompiled-binaries" option to "cwebp-120-linux-x86-64"
Checking checksum for supplied binary: [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64
Checksum test took: 34 ms
Creating command line options for version: 1.1.0
The near-lossless option ignored for lossy
Trying to convert by executing the following command:
nice [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64 -metadata none -q 65 -alpha_q '60' -sharp_yuv -m 6 -low_memory '[doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png' -o '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp.lossy.webp' 2>&1

*Output:* 
libpng warning: iCCP: known incorrect sRGB profile
Saving file '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp.lossy.webp'
File:      [doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png
Dimension: 80 x 17 (with alpha)
Output:    882 bytes Y-U-V-All-PSNR 37.36 36.20 39.28   37.40 dB
           (5.19 bpp)
block count:  intra4:          5  (50.00%)
              intra16:         5  (50.00%)
              skipped:         0  (0.00%)
bytes used:  header:             80  (9.1%)
             mode-partition:     42  (4.8%)
             transparency:      151 (42.4 dB)
 Residuals bytes  |segment 1|segment 2|segment 3|segment 4|  total
  intra4-coeffs:  |     386 |       0 |       0 |       0 |     386  (43.8%)
 intra16-coeffs:  |      10 |       0 |       0 |       0 |      10  (1.1%)
  chroma coeffs:  |     153 |       0 |       0 |       0 |     153  (17.3%)
    macroblocks:  |      100%|       0%|       0%|       0%|      10
      quantizer:  |      30 |      30 |      30 |      30 |
   filter level:  |       9 |       9 |       9 |       9 |
------------------+---------+---------+---------+---------+-----------------
 segments total:  |     549 |       0 |       0 |       0 |     549  (62.2%)
Lossless-alpha compressed size: 150 bytes
  * Header size: 62 bytes, image data size: 88
  * Lossless features used: PALETTE
  * Precision Bits: histogram=3 transform=3 cache=0
  * Palette size:   14

Executing cwebp binary took: 12 ms

Success
Reduction: 75% (went from 3593 bytes to 882 bytes)

Converting to lossless
Looking for cwebp binaries.
Discovering if a plain cwebp call works (to skip this step, disable the "try-cwebp" option)
- Executing: cwebp -version 2>&1. Result: *Exec failed* (the cwebp binary was not found at path: cwebp, or it had missing library dependencies)
Nope a plain cwebp call does not work (spent 4 ms)
Discovering binaries using "which -a cwebp" command. (to skip this step, disable the "try-discovering-cwebp" option)
Found 0 binaries (spent 15 ms)
Discovering binaries by peeking in common system paths (to skip this step, disable the "try-common-system-paths" option)
Found 0 binaries (spent 0 ms)
Discovering binaries which are distributed with the webp-convert library (to skip this step, disable the "try-supplied-binary-for-os" option)
Checking if we have a supplied precompiled binary for your OS (Linux)... We do. We in fact have 4
Found 4 binaries (spent 0 ms)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-103-linux-x86-64-static
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-061-linux-x86-64
Discovering cwebp binaries took: 19 ms

Binaries ordered by version number.
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64: (version: 1.2.0)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64: (version: 1.1.0)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-103-linux-x86-64-static: (version: 1.0.3)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-061-linux-x86-64: (version: 0.6.1)
Starting conversion, using the first of these. If that should fail, the next will be tried and so on.
Checking checksum for supplied binary: [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64
Checksum test took: 40 ms
Creating command line options for version: 1.2.0
Trying to convert by executing the following command:
nice [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64 -metadata none -q 65 -alpha_q '60' -near_lossless 60 -sharp_yuv -m 6 -low_memory '[doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png' -o '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp.lossless.webp' 2>&1

*Output:* 
[doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64: /lib/x86_64-linux-gnu/libm.so.6: version `GLIBC_2.29' not found (required by [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64)

Executing cwebp binary took: 5 ms

Exec failed (return code: 1)
Note: You can prevent trying this precompiled binary, by setting the "skip-these-precompiled-binaries" option to "cwebp-120-linux-x86-64"
Checking checksum for supplied binary: [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64
Checksum test took: 34 ms
Creating command line options for version: 1.1.0
Trying to convert by executing the following command:
nice [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64 -metadata none -q 65 -alpha_q '60' -near_lossless 60 -sharp_yuv -m 6 -low_memory '[doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png' -o '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp.lossless.webp' 2>&1

*Output:* 
libpng warning: iCCP: known incorrect sRGB profile
Saving file '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png.webp.lossless.webp'
File:      [doc-root]/wp-content/plugins/clearfy/libs/factory/pages/templates/impressive/assets/img/5-stars22.png
Dimension: 80 x 17
Output:    452 bytes (2.66 bpp)
Lossless-ARGB compressed size: 452 bytes
  * Header size: 265 bytes, image data size: 162
  * Lossless features used: PALETTE
  * Precision Bits: histogram=3 transform=3 cache=0
  * Palette size:   116

Executing cwebp binary took: 9 ms

Success
Reduction: 87% (went from 3593 bytes to 452 bytes)

Picking lossless
cwebp succeeded :)

Converted image in 224 ms, reducing file size with 87% (went from 3593 bytes to 452 bytes)
