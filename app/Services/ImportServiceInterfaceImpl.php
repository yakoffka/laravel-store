<?php

namespace App\Services;

use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class ImportServiceInterfaceImpl implements ImportServiceInterface
{

    /*
     * @param string $archImagesName
     * @return bool|RedirectResponse
     * @todo: заменить return back()->withErrors() на through/catch
     */
	/*public function prepareImages(string $archImagesName)
	{
        if (!Storage::disk('import')->exists($archImagesName)) {
            info(__METHOD__ . '@' . __LINE__ . ': архив с изображениями не обнаружен.');
            return true;
        }

        // @todo: после тестирования вернуть переименование! copy->move
        Storage::disk('import')->copy($archImagesName, 'images.zip');

        $zip = new ZipArchive();
        if ($zip->open(Storage::disk('import')->path('images.zip')) === true) {
            $zip->extractTo(Storage::disk('import')->path('temp/images'));
            $zip->close();
            Storage::disk('import')->delete('images.zip');
            return true;
        }

        $mess = __(': Failed to process image archive');
        info(__METHOD__ . '@' . __LINE__ . $mess);
        return back()->withErrors([' err' . __LINE__ . $mess]);
    }*/

    /**
     * @param string $csvPath
     */
	public function import(string $csvPath)
	{
        Excel::import(new ProductImport, Storage::disk('import')->path($csvPath));
	}

    /**
     * @inheritDoc
     */
    public function cleanUp()
    {
        // TODO: Implement cleanUp() method.
    }
}
