# Buoy

Buoy is a command line application that reports and analyze data collected by buoys around Taiwan.

## Installation

- `git clone` this project to your local file directory
- `cd <Buoy Directory>` change to the Buoy directory where composer.json
  resides
- Run `composer install` to get required components from Packagist.

## Usage

- At project folder root, run `php index.php` to report the buoys at northern Taiwan.
- A list of available buoy station names is presented. Use <space> key to select one station of interest; <return> key to display its report.
- Buoy data of the last 3 hours are printed, along with the stats (default to average and trend) of the last 8-hour data (wave height, sea temperature, and air temperature) are printed to stdout.
- A confirmation dialog follows. Select 'y' to repeat buoy selection; 'n' to quit the program.

## To-do's

- [x] [Issue #1](https://github.com/Chien-pinWang/Buoy/issues/1): Select and report one buoy at a time
- [x] [Issue #2](https://github.com/Chien-pinWang/Buoy/issues/2): Show PHP error when no buoy was selected
- [x] [Issue #3](https://github.com/Chien-pinWang/Buoy/issues/3): Add a mail handler to logger for Error level log
- [x] [Issue #4](https://github.com/Chien-pinWang/Buoy/issues/4): Replace simple_html_dom by the Guzzlehttp/guzzle package

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

- 01/10/2017 1.3.1 Improved performance by using Guzzlehttp and DOMDocument instead of simple_html_dom
- 01/06/2017 1.3.0 Added a mail log handler if log level >= Error
- 01/03/2017 1.2.2 Select and show one buoy report at a time
- 12/27/2016 1.2.1 Bug fix on trend testing condition.
- 12/27/2016 1.2.0 Verbose level of report and better trend algorithm
- 12/26/2016 1.1.1 Fix to suppress message if no buoy was selected
- 12/26/2016 1.1.0 Buoy results in table format.
- 12/16/2016 1.0.0 First release.

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

