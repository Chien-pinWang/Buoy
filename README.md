# Buoy

Buoy is a command line application that reports and analyze data collected by buoys around Taiwan.

## Installation

- `git clone` this project to your local file directory
- Run `composer install` to get required components from Packagist.

## Usage

- At project folder root, run `php index.php` to report the buoys at northern Taiwan.
- A list of available buoy station names is presented. Use <space> key to select the stations of interest; <return> key to start the program.
- Buoy data of the last 3 hours are printed, along with the stats (average, max, min, and trend) of the last 8-hour data (wave height, sea temperature, and air temperature) are printed to stdout.

## To-do's

- Let index.php handle all the display rather than the get functions in class

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

12/26/2016 1.1.0 Enhanced user interface.
12/16/2016 1.0.0 First release.

## Credits

- Buoy data is provided by the [real-time marine conditions](http://cwb.gov.tw/V7/observe/marine/) of the [Central Weather Bureau](http://cwb.gov.tw) of Taiwan.
- Thanks to the book [Modern PHP](https://www.amazon.com/Modern-PHP-Features-Good-Practices-ebook/dp/B00TKVLL26/ref=mt_kindle?_encoding=UTF8&me=) that teaches me new features and good practices of modern PHP programming.

## License

Copyright © 2016 Chien-pin Wang <Wang.ChienPin@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

