WebP Express 0.20.1. Conversion triggered with the conversion script (wod/webp-on-demand.php), 2021-09-24 10:50:20

*WebP Convert 2.6.0*  ignited.
- PHP version: 7.4.23
- Server software: nginx/1.20.1

Stack converter ignited

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg.webp
- log-call-arguments: true
- converters: (array of 10 items)

The following options have not been explicitly set, so using the following defaults:
- auto-limit: true
- converter-options: (empty array)
- shuffle: false
- preferred-converters: (empty array)
- extra-converters: (empty array)

The following options were supplied and are passed on to the converters in the stack:
- default-quality: 60
- encoding: "lossy"
- max-quality: 70
- metadata: "none"
- quality: "auto"
------------


*Trying: cwebp* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg.webp
- default-quality: 60
- encoding: "lossy"
- low-memory: true
- log-call-arguments: true
- max-quality: 70
- metadata: "none"
- method: 6
- quality: "auto"
- use-nice: true
- try-common-system-paths: true
- try-supplied-binary-for-os: true
- command-line-options: ""

The following options have not been explicitly set, so using the following defaults:
- alpha-quality: 85
- auto-limit: true
- auto-filter: false
- near-lossless: 60
- preset: "none"
- size-in-percentage: null (not set)
- sharp-yuv: true
- skip: false
- try-cwebp: true
- try-discovering-cwebp: true
- rel-path-to-precompiled-binaries: *****
- skip-these-precompiled-binaries: ""
------------

Looking for cwebp binaries.
Discovering if a plain cwebp call works (to skip this step, disable the "try-cwebp" option)
- Executing: cwebp -version 2>&1. Result: *Exec failed* (the cwebp binary was not found at path: cwebp, or it had missing library dependencies)
Nope a plain cwebp call does not work (spent 13 ms)
Discovering binaries using "which -a cwebp" command. (to skip this step, disable the "try-discovering-cwebp" option)
Found 0 binaries (spent 78 ms)
Discovering binaries by peeking in common system paths (to skip this step, disable the "try-common-system-paths" option)
Found 0 binaries (spent 0 ms)
Discovering binaries which are distributed with the webp-convert library (to skip this step, disable the "try-supplied-binary-for-os" option)
Checking if we have a supplied precompiled binary for your OS (Linux)... We do. We in fact have 4
Found 4 binaries (spent 0 ms)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-103-linux-x86-64-static
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-061-linux-x86-64
Discovering cwebp binaries took: 91 ms

Binaries ordered by version number.
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64: (version: 1.2.0)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64: (version: 1.1.0)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-103-linux-x86-64-static: (version: 1.0.3)
- [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-061-linux-x86-64: (version: 0.6.1)
Starting conversion, using the first of these. If that should fail, the next will be tried and so on.
Checking checksum for supplied binary: [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64
Checksum test took: 115 ms
Creating command line options for version: 1.2.0
*Setting "quality" to "auto" is deprecated. Instead, set "quality" to a number (0-100) and "auto-limit" to true. 
*"quality" has been set to: 70 (took the value of "max-quality").*
*"auto-limit" has been set to: true."*
Running auto-limit
Quality setting: 70. 
Quality of jpeg: 100. 
Auto-limit result: 70 (no limiting needed this time).
The near-lossless option ignored for lossy
Trying to convert by executing the following command:
nice [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64 -metadata none -q 70 -alpha_q '85' -sharp_yuv -m 6 -low_memory '[doc-root]/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg' -o '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg.webp' 2>&1

*Output:* 
[doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64: /lib/x86_64-linux-gnu/libm.so.6: version `GLIBC_2.29' not found (required by [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-120-linux-x86-64)

Executing cwebp binary took: 87 ms

Exec failed (return code: 1)
Note: You can prevent trying this precompiled binary, by setting the "skip-these-precompiled-binaries" option to "cwebp-120-linux-x86-64"
Checking checksum for supplied binary: [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64
Checksum test took: 79 ms
Creating command line options for version: 1.1.0
The near-lossless option ignored for lossy
Trying to convert by executing the following command:
nice [doc-root]/wp-content/plugins/webp-express/vendor/rosell-dk/webp-convert/src/Convert/Converters/Binaries/cwebp-110-linux-x86-64 -metadata none -q 70 -alpha_q '85' -sharp_yuv -m 6 -low_memory '[doc-root]/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg' -o '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg.webp' 2>&1

*Output:* 
Saving file '[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg.webp'
File:      [doc-root]/wp-content/uploads/2021/08/truckdriver6141911920_derived_800x400.jpg
Dimension: 800 x 400
Output:    35284 bytes Y-U-V-All-PSNR 37.40 36.87 38.22   37.43 dB
           (0.88 bpp)
block count:  intra4:        778  (62.24%)
              intra16:       472  (37.76%)
              skipped:        65  (5.20%)
bytes used:  header:            261  (0.7%)
             mode-partition:   3401  (9.6%)
 Residuals bytes  |segment 1|segment 2|segment 3|segment 4|  total
  intra4-coeffs:  |   19916 |     191 |     174 |      82 |   20363  (57.7%)
 intra16-coeffs:  |    1190 |     231 |     212 |     260 |    1893  (5.4%)
  chroma coeffs:  |    8367 |     270 |     351 |     351 |    9339  (26.5%)
    macroblocks:  |      58%|       6%|       9%|      27%|    1250
      quantizer:  |      36 |      28 |      19 |      16 |
   filter level:  |      23 |      18 |      26 |       7 |
------------------+---------+---------+---------+---------+-----------------
 segments total:  |   29473 |     692 |     737 |     693 |   31595  (89.5%)

Executing cwebp binary took: 3484 ms

Success
cwebp succeeded :)

Converted image in 3948 ms, reducing file size with 92% (went from 416 kb to 34 kb)
