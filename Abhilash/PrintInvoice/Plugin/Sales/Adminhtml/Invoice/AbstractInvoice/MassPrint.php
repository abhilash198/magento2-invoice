<?php
namespace Abhilash\PrintInvoice\Controller\Sales\Adminhtml\Invoice\AbstractInvoice;

use Magento\Sales\Controller\Adminhtml\Invoice\AbstractInvoice\Pdfinvoices;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class MassPrint
{

    /**
     * Save collection items to pdf invoices
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface
     * @throws \Exception
     */
    public function massAction(AbstractCollection $collection)
    {
        $pdf = $this->pdfInvoice->getPdf($collection);
        $fileContent = ['type' => 'string', 'value' => $pdf->render(), 'rm' => true];

        return $this->fileFactory->create(
            sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
            $fileContent,
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }
}
