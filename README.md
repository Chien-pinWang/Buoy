# Buoy

Buoy is a command line application that reports and analyze data collected by buoys around Taiwan.

## Installation

- <code>git clone</code> this project to your local file directory
- Run <code>composer install</code> to get required components from Packagist.

## Usage

- At project folder root, run <code>php index.php</code> to report the buoys at northern Taiwan.
- An optional command line argument <code>php index.php S</code> reports the buoys at southern Taiwan.
- Buoy data of the last 3 hours are printed, along with the stats (average, max, min, and trend) of the last 8-hour data (wave height, sea temperature, and air temperature) are printed to stdout.

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

12/16/2016 First release.

## Credits

Thanks to the book [Modern PHP](https://www.amazon.com/Modern-PHP-Features-Good-Practices-ebook/dp/B00TKVLL26/ref=mt_kindle?_encoding=UTF8&me=) that teaches me new features and good practices of modern PHP programming.

## License

MIT.
