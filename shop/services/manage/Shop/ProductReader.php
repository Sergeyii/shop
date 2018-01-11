<?php

namespace services\manage\Shop;

use values\shop\product\ProductCsvRow;

class ProductReader
{
    /**
     * @return ProductCsvRow[]
     */
    public function readCSV($fileName)
    {
        $file = fopen($fileName, 'r');

        //$result = [];
        while($row = fgetcsv($file, 1024) ){
            $productRow = new ProductCsvRow();
            $productRow->code = $row[0];
            $productRow->price_old = $row[1];
            $productRow->price_new = $row[2];
            $productRow->modification = $row[3];
            $productRow->modification_price = $row[4];

            //$result[] = $productRow;

            yield $productRow;
        }

        fclose($file);

        //return $result;
    }
}