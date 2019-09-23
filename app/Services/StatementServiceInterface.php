<?php
/**
 * Created by PhpStorm.
 * User: Specter
 * Date: 20.09.2019
 * Time: 22:59
 */

namespace App\Services;


/**
 * Interface StatementServiceInterface
 * @package App\Services
 */
interface StatementServiceInterface
{
    /**
     * @return statement for customer in excel file
     */
    public function generateStatementExcel($id);

    /**
     * @return statement for customer in pdf file
     */
    public function generateStatementPdf($id);
}