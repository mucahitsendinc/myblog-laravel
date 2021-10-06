<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['setting'=>'access','option'=>'WUU4ZHVjcEVJd04ramx3UmpWaExDT0lvRjQyQUhURnhweG0zbUNSd1VELzVTUEJ2c3Q2Yzc1dXNubUF0SEl2cW1BaHZ2NnN3MHdrMm5GdVR3VFdkRi9DVHdaSnZSa1BoanA5SndvcG9QRzlzampNbVF4WDZ4Rnl0NVMyQWRDNVFKcytyQnV2TVBWV2tZd0NjWGtGdHdQV0NDODBtdTRyeDF2MlNRcHNaUnlrcnFHTGY4T0kycm42cjZ6RktDWWlaaEFpdDEzYXpJbElSaWhkRWsrN20xQ1lBTkFvMWxPY0wvSEphdkNOVk5xb3BOSzBuMzdLTWhIeXdVZWJUNFdUMHBxOVFlWGY1WHBLdUlzNHo2OVE4ajFGaXNrL2RBQ2lOWTJncEpMdlcwb1ZOVE5LN3BmSzFMUUhJeEJSWU4ydnptd05rckRieDlhaWMwbGR1QWZJNHg1TExDS09iRmhKdUpoaEZWbUQ3K2RSZ3FKOHo1Um5GU3J3WG05UUttcEFkQ3pudVcrbE03aUFxL01RbnJLOGhLWnBrbndnY3A0N0Z0UWdzbU1zZUN3OHhXamN1TlV3TUFJbmUxL1h4QmxzVXRsbFB1b3hjNlNuemJpZWpRRU91ZU9JWWtFOGFmL1VYQ0JsVUpnR3NSREJjNFlacVRYUjlxbjJ0QjkvUVlYT281cDZpcWNYTGI3RXBMVERPbzVJR1M3ZFQ5ZXptd3hSY3RQelRlb3Yxak1CSjNyVjg5Z244T3d1d0xaUUQ3YzVJQm1CTXVYdnhlSGNFZWplL0JhSmhjNzBTamhXbTE2bXB1Yjk1UUJTTDl6MGJvb0x0QlljSnJQUmFtNVZteExYcUpkYWt2ZC8vUVpMU0IwNm1EbUtHcXdxWHdJY1NnUkczY3hUM1p3VDUvM3J0TXJONU9vQ1NFNFp5UXkyTUFLZ0JpVGlGdFFrZ0dYRlpCMXhBSGZrQ2pmZGJmRXE5cFU4Q3NkMDFPelQ0RjhCeGYwYm1KaFd6Y0ZIeDNWdndZakJiQlZQc0VGM3ZxYUZLMEd0UzVwOERZOTh5RWcwdFFpSWdwd2Y3TGhya0YyYUpQZ3o2ZWgwSWlWaUJWaGpHT3Z0ZFcySDJKY0grOWZtSitCTjBkV0NtbTNrbXFoNFZ6eXdRQm5BOEgzL0ZGUDdGMnJsV3JRT1g3RXVXNlNBUHZCTDBCVjVhR2o5cnFsZjZSZVlGQmpKRFp0VzEvRHZPcGFlVi8vbFYvM3hxVGIrOVUxdDNXcURNSmxwSVhLa1loNEQvei80SEFxaU1xS0Zlam1KdmdUK3Q4T1VjdjVIVnNQWllhQlgrNkU4VHlyQzYrd1l6REtKTllkemVnWXdaYlhrT3VqM3puTkU5dTJUUklsU0lGbnZ0ajRiWjE3ZTdhaXNpSklLUUZVZ2JGSCtPalkxRkdOT3diY3hWMytkOVIvdjJmVytXZytTTi9OSkl3cU5wWUY3MkxCWlF6enE3ZHBpUThoTmx5NVVIbEt3akFvdi9MeVBZZXhEQ0hBUGp4VVVjZHdHQWhYM0FCV252bkE5RldRNmxXaWtLUkNvUkxoVFc1NGQ5ckp4Ymg5ZGtiNHhnM0dRRXgvUmhSZURoeXVyTmcydmxWdHRVNUtKcldyUFVEWitTSWVsT2V2c0NMSHNvdG4weGdiOHJtNzVkL2xBVkNDTW1MZTIyNm9lR3lyam9LN1JtQXlsM2JER3B3ZGdtZnFBWlpDeVhmMHdqWXFoTVA3ajBsN2Z6cXc2VkZybngrK1FWYUtpdUVEQjduSmJHVWVVcUdRL0llWFlZZFZ4OQ=='],
            ['setting'=>'contact','option'=>'enable'],
            ['setting'=>'comment','option'=>'enable'],
            ['setting'=>'post','option'=>'enable'],
            ['setting'=>'recommended','option'=>'enable',],
            ['setting'=>'imageupload','option'=>'enable',],
            ['setting'=>'imagedelete','option'=>'enable'],
            ['setting'=>'search','option'=>'enable']
        ]);
    }
}
