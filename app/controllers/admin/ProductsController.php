<?php
require 'auth.php';
require_once '../app/models/Model.php';
require_once '../app/models/admin/Product.php';
require_once '../app/models/admin/Category.php';

use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\VariantAttributes;

class ProductsController
{
    private $product;
    private $category;
    private $mysqli;
    private  $variantModel;
    private  $attributeModel; 

    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;
        $this->category = new Category();
        $this->product = new Product();
        $this->variantModel = new ProductVariant();
        $this->attributeModel = new VariantAttributes();


    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            
            // --- Validar y limpiar datos base ---
            $name        = trim($_POST['name'] ?? '');
            $sku         = trim($_POST['sku'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $unit        = trim($_POST['unit'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            $vat_rate    = trim($_POST['vat_rate'] ?? '0');
            $language    = "EN";
    
            if (empty($name) || empty($unit) || empty($category_id)) {
                echo "El nombre,  unidad y categoría son obligatorios.";
                return;
            }
    
            $category = $this->category->getById($category_id);
            $categoryName =$category['name']; 

            $sku= $categoryName. "-" .$name;

            // --- Guardar producto id ---
            $productData = [
                'name'        => $name,
                'sku'         => $sku,
                'description' => $description,
                'unit'        => $unit,
                'category_id' => $category_id,
                'vat_rate'    => $vat_rate,
                'language'    => $language,
                'user_id'     => $user_id
            ];
    
            $productId = $this->product->create($productData);
            
            if (!$productId) {
                echo "Error al guardar el producto principal.";
                return;
            }
    
            // --- Procesar variantes ---
            $variants = $_POST['variants'] ?? [];
            
            if (!empty($variants)) {
    
                foreach ($variants as $vIndex => $variant) {
                   
                    $imageUrl = '';
                    $variantSKU = trim($variant['sku'] ?? '');
                    $firstAttributeValue = $variant['attributes'][0]['atributo_valor'] ?? $name;

                    // Subir imagen si existe
                
                    // foreach ($_FILE('variants'))
                  //  if (isset($_FILES['variants']) && !empty($_FILES['variants']['tmp_name'][$vIndex]['image_file'])) {
                        $fileTmp = $_FILES['variants']['tmp_name'][$vIndex]['image_file'];
                        $originalName = $_FILES['variants']['name'][$vIndex]['image_file'];
                        // $categoryName = $variant['category_name'] ?? 'general';
                    
                        $uploadResult = $this->upload($productId, $categoryName, $name, $firstAttributeValue, $fileTmp, $originalName);
                    
                        if ($uploadResult['success']) {
                            $imageUrl = $uploadResult['url'];
                            $variantSKU = $uploadResult['sku'];
                        }
                    //}
    
                    $cleanVariant = [
                        'product_id' => $productId,
                        'sku'        => $variantSKU,
                        'price'      => floatval($variant['price'] ?? 0),
                        'stock'      => intval($variant['stock'] ?? 0),
                        'weight'     => floatval($variant['weight'] ?? 0),
                        'image_url'  => $imageUrl,
                        'is_active'  => isset($variant['is_active']) ? intval($variant['is_active']) : 1,
                        'user_id'    => $user_id
                    ];
    

                    // $variantId = $variantModel->create($cleanVariant);
                    $variantId = $this->variantModel->create($cleanVariant);


                    // --- Guardar atributos ---
                    foreach ($variant['attributes'] as $attr) {
                        $atributo       = trim($attr['atributo'] ?? '');
                        $atributo_valor = trim($attr['atributo_valor'] ?? '');
                        if ($atributo && $atributo_valor) {
                            $this->attributeModel->create([
                                'variant_id'     => $variantId,
                                'atributo'       => $atributo,
                                'atributo_valor' => $atributo_valor
                            ]);
                        }
                    }
                    
                }
            }
    
            // --- Redirigir al listado de productos ---
            header('Location: ./index.php?page=products&action=index');
            exit;
    
        } else {
            // Mostrar formulario de creación
            $categories = $this->category->getAll();
            include '../app/views/admin/products/create.php';
        }
    }
    
    // --- Método interno para subir imágenes ---
    private function upload($productId, $categoryName, $productName, $firstAttributeValue, $tmpFile, $originalName)
    {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $logFile = $logDir . '/upload.log';
        file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] Iniciando uploadInternal...\n", FILE_APPEND);
    
        $baseDir = __DIR__ . '/../../../uploads/products'; // localhost, sin public_html
        $productDir = $baseDir . "/" . $productId . "/" . 'images/';
        if (!is_dir($productDir)) mkdir($productDir, 0777, true);
    
        // Generar base legible
        $nameBase = strtolower($categoryName . '-' . $productName);
        $nameBase = preg_replace('/[^a-zA-Z0-9_-]/', '-', $nameBase);

        // Generar 6 caracteres únicos
        $unique = substr(bin2hex(random_bytes(3)), 0, 6); // 3 bytes → 6 chars hex

        // Combinar todo y mantener extensión .png
        $newName = $nameBase . '-' . $unique . '.png';

        // Ruta final
        $destination = $productDir . DIRECTORY_SEPARATOR . $newName;

    
        // --- Cargar la imagen original ---
        $info = getimagesize($tmpFile);
        $imgType = $info[2];
    
        switch ($imgType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($tmpFile);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($tmpFile);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($tmpFile);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($tmpFile);
                break;
            default:
                file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] Formato no soportado: {$originalName}\n", FILE_APPEND);
                return ['success' => false, 'error' => 'Formato no soportado'];
        }
    
        // --- Redimensionar a 16:9 ---
        // $maxWidth = 1920;
        // $maxHeight = 1080;
        $maxWidth = 500;
        $maxHeight = (int)($maxWidth * 16 / 9); // mantiene 9:16
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
    
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)($origWidth * $ratio);
        $newHeight = (int)($origHeight * $ratio);
    
        $resized = imagecreatetruecolor($newWidth, $newHeight);
    
        // Transparencia PNG
        imagesavealpha($resized, true);
        $trans_color = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $trans_color);
    
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    
        // --- Guardar como PNG ---
        if (imagepng($resized, $destination)) {
            file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] Archivo movido correctamente: {$destination}\n", FILE_APPEND);
            imagedestroy($image);
            imagedestroy($resized);
            return [
                'success' => true,
                'url'     => 'products/' . $productId . '/images/' . $newName,
                'sku'     => $nameBase
            ];
        } else {
            file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] ERROR al mover archivo: {$destination}\n", FILE_APPEND);
            imagedestroy($image);
            imagedestroy($resized);
            return ['success' => false];
        }
    }
    
   public function index()
    {
        // Obtener el valor de búsqueda (si existe)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Configuración de paginación
        $productsPerPage = 10;
        $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $offset = ($currentPage - 1) * $productsPerPage;

        // Si hay búsqueda, usamos searchByName(); sino, getAllPaginated()
        if (!empty($search)) {
            $products = $this->product->searchByName($search);
        } else {
            $products = $this->product->getAllPaginated($productsPerPage, $offset);
        }

        // Obtener total de registros (filtrados si hay búsqueda)
        $totalProducts = $this->product->getTotal($search);
    $totalPages = ceil($totalProducts / $productsPerPage);

    $categories = $this->category->getAll();
        
        // Pasar datos a la vista
        include '../app/views/admin/products/index.php';
    }

// 🔍 SEARCH: para manejar búsquedas por POST (formulario o AJAX)
public function search()
{
    // Capturar el texto de búsqueda
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';

    // Configuración de paginación
    $productsPerPage = 10;
    $currentPage = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
    $offset = ($currentPage - 1) * $productsPerPage;

    if (!empty($search)) {
        $products = $this->product->searchByName($search);
    } else {
        $products = $this->product->getAllPaginated($productsPerPage, $offset);
    }

    // Obtener total de productos (filtrados si hay búsqueda)
    $totalProducts = $this->product->getTotal($search);
    $totalPages = ceil($totalProducts / $productsPerPage);

    // Agregar nombre de categoría y variantes con atributos
    foreach ($products as &$product) {
        $category = $this->category->getById($product['category_id']);
        $product['category_name'] = $category ? $category['name'] : 'Sin categoría';

        $product['variants'] = $this->variantModel->getByColumn('product_id', $product['id']);
        foreach ($product['variants'] as &$variant) {
            $variant['attributes'] = $this->attributeModel->getByColumn('variant_id', $variant['id']);
        }
    }
    unset($variant);
    unset($product);

    // Cargar vista
    include '../../app/views/admin/products/index.php';
}

public function delete($id)
{
     $id = intval($id); // Asegurar que sea un número
  
     $productId = $this->product->delete($id);

    // Redirigir al listado de productos
    header('Location: ./index.php?page=products&action=index');
    exit;
}

}


