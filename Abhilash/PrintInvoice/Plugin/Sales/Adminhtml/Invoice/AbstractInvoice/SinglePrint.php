<?php
/**
 * 
 */
namespace Abhilash\PrintInvoice\Plugin\Sales\Adminhtml\Invoice\AbstractInvoice;

use Magento\Sales\Controller\Adminhtml\Invoice\AbstractInvoice\PrintAction;
use Exception;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;

class SinglePrint
{  
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;
 
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * 
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceConnection $resource,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;       
        $this->logger = $logger;
    }
    
    /**
     * 
     * @param PrintAction $subject
     * @return type
     */
    public function beforeExecute(PrintAction $subject)
    {
        $invoiceId = $subject->getRequest()->getParam('invoice_id');
        try {
            $connection  = $this->resource->getConnection();
            $data = ["is_printed"=> 1]; 
            $where = ['entity_id = ?' => (int)$invoiceId];
            $salesInvoice = $connection->getTableName("sales_invoice");
            $salesInvoiceGrid = $connection->getTableName("sales_invoice_grid");
            $connection->update($salesInvoice, $data, $where);
            $connection->update($salesInvoiceGrid, $data, $where);
        } catch (Exception $exception)  {
            $this->logger->critical($exception->getMessage());
        }
        return [$subject];
    }
}
