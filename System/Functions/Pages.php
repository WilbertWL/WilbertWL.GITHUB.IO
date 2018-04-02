<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pages
 *
 * @author Administrador
 */

class Pages extends AdminManager {
    
# Funciones Admin-Manager  ---------------------------------------
    public function Manager_CurrentPage_Descript(){ return $this->Manager_CurrentPage("Descrip"); }
    public function Manager_CurrentPage_Folder(){ return $this->Manager_CurrentPage("Folder"); }
    public function Manager_CurrentPage_CSS(){ 
        $css = $this->Manager_CurrentPage("UrlCSS");
        $e ="";
        if($css!=""):
            $file = str_split($css, ";");
            foreach ($file as $value) {
                if($value!=""):
                    $e .= '\n'.'<link href="'.Path_Plugins.$this->Manager_CurrentPage('Folder').$value.'" rel="stylesheet" />';
                endif;
            }
        endif;
        return $e!=""? $e : "";
    }
    public function Manager_CurrentPage_JS(){
        $js = $this->Manager_CurrentPage("UrlJS"); 
        $e ="";
        if($js!=""):
            $file = str_split($js, ";");
            foreach ($file as $value) {
                if($value!=""):
                    $e .= "\n".(string)'<script src="'.Path_Plugins.$this->Manager_CurrentPage('Folder').$value.'"></script>';
                endif;
            }
        endif;
        return $e!=""? $e : "";
    }
    
    public function Manager_CurrentPage_HTML(){
        $e ="";
        return $e!=""? $e :"Not Found";
    }

    public function Manager_CurrentPage_WindowPlugin_Config(){ 
        return $this->IncludeFile(PLUGINS.$this->Manager_CurrentPage_Folder()."/".$this->Manager_CurrentPage("FileName"));        
    }
        
    public function Manager_CurrentPage_Exist(){ 
        $DataPage = FALSE;
        if($this->O(TRUE)):
            $C = $this->ReadPropertiesAllPlugins();
            for ($i = 0; $i < count($C); $i++) {
                if($C[$i]["Type"] == "Plugin" and $C[$i]["NameURL"] == $this->O()):
                    $DataPage = TRUE;
                endif;
            }
        endif;
        return $DataPage;
    }
    
    private function Manager_CurrentPage($data){
        $DataPage = "";
        if($this->O(TRUE)):
            $C = $this->ReadPropertiesAllPlugins();
            for ($i = 0; $i < count($C); $i++) {
                if($C[$i]["Type"] == "Plugin" and $C[$i]["NameURL"] == $this->O()):
                    $DataPage = $C[$i][$data];
                endif;
            }
        endif;
        return $DataPage;
    }
# Funciones Admin-Manager  ---------------------------------------
    
    
    
    
    private function PagesSite(){
        $pagesWebsite = json_decode(parent::GetFileContents(PAGES."Pages.json"), TRUE);
        return $pagesWebsite;
    }
    
    public function DefaultPage(){
        $pagesWebsite = $this->PagesSite();
        for ($i = 0; $i < count($pagesWebsite); $i++) {
            if($pagesWebsite[$i]["Default"]):
                return $pagesWebsite[$i]["Name"];
            else:
                return "Internal Error: Pagina por defecto no asignada";
            endif;
        }
    }
    
    public function OpenPage($page){
        $e = "<div class='jumbotron'><h1 class='display-4 text-center'>Page Not Found</h1></div><br />\n";
        if($this->PageExist($page)):
            $e = $page;
        endif;
        return $e;
    }
    
    public function PageExist($page){
        $e = FALSE;
        for ($i = 0; $i < count($this->PagesSite()); $i++) {
            if($this->PagesSite()[$i]["Name"] == $page):
                $e = TRUE;
                $i = count($this->PagesSite());
            endif;
        }
        return $e;
    }
    
    # Admin Manager ------------------------------------------------------
    
    
    #  New TEST ----------------------------------------------
    
    public function PageData($page = "Build", $save = false){
        if($save):
            $newData = json_encode(simplexml_load_string($this->GetFileContents(PAGES.$page.".xml")));
            file_put_contents(PAGES.$page.".gsf", $newData);
        else:
            print_r($this->JsonToXML(json_decode($this->GetFileContents(PAGES.$page.".gsf"), TRUE)));
        endif;
    }
    
    public function JsonToXML($array = [], DOMDocument $xml){
        foreach ($array as $key => $value) {
            $item = $xml->createElement($key);
            $attrib = $xml->createAttribute($value["@attributes"]);
            $xml->appendChild($item);
        }
        $xml->formatOutput = true;
        $xml->saveXML();
        return $xml;
    }
    
    public function crear($page = "Build"){
    $xml = new DOMDocument();
    //$xml->load(""); // el archivo existe
    $contactos = $xml->createElement('contactos','');
    $xml->appendChild($contactos);
    
    /*************************/
    
    // Creo el atributo
    $atributo = $xml->createAttribute('xmlns');
    // Se lo acoplo al elemento "contactos"
    $contactos->appendChild($atributo);    
    // Creo el texto
    $atributo_valor = $xml->createTextNode('MiXml');
    // Se lo asigno al atributo
    $atributo->appendChild($atributo_valor);
    
    /*************************/
    
    $persona = $xml->createElement('persona',''); 
    $contactos->appendChild($persona);
    
    $nombre = $xml->createElement('nombre',''); 
    $persona->appendChild($nombre); 
    
    $telefono = $xml->createElement('telefono','');  
    $persona->appendChild($telefono); 
      
    //$xml->save("RegistroContactos.xml"); 
 
    $xml->formatOutput = true;
    
    
    
    print_r($xml->saveXML());
//$el_xml = $xml->saveXML();
    //$xml->save('libros.xml');
 
  }
    
    private function attributes_SavePageData($array){
        foreach ($array as $key => $val) {
            if(count($val)>=0):
                $data .= $key." =>". $this->attributes_SavePageData($val);
            else:
                $data .= $key." =>". $val;
            endif;
        }
        return $data;
    }


    #  New TEST ----------------------------------------------
    
    
    /*
    
    public function newPageData(){
        $e = new Encrypt();
        //$dataNew = $e->Encode($this->GetFileContents(PAGES."Build.xml"));
        $dataNew = $this->GetFileContents(PAGES."Build.xml");
        
        $this->UpdatePage($dataNew, "Build");
    }
    
    public function ReadPageData() {
        $this->ReadPage("Build");
    }
    
    public function ReadConfigPage($page) {
        $file = parent::GetFileContents(PAGES.$page.".xml");
        $e = simplexml_load_string($file);
        $e1 = json_encode($e);
        $e2 = json_decode($e1, true);
        return $e2;
    }
    
    private function UpdatePage($dataNew, $page){
        $e = new Encrypt();
        $x = 1; $newDataFormat = "";
        
        $json = $e->Encode(json_encode(simplexml_load_string($dataNew)));
        
        for ($i = 0; $i < strlen($json); $i++) {
            if(($i/(80*$x))==1):
                $newDataFormat .="\n";
                $x++;
            endif;
            $newDataFormat .= $json[$i];
        }
        file_put_contents(PAGES.$page.".gsf", $newDataFormat);
    }

    private function ReadPage($page){
        $e = new Encrypt();
        if($this->GetFileContents(PAGES.$page.".gsf", TRUE)):
            $a = str_replace("\n", "", $this->GetFileContents(PAGES.$page.".gsf"));
            
            $json = json_decode($e->Decode($a), TRUE);
            $xml_user_info = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><html />');
            print $this->array_to_xml($json, $xml_user_info);
        else:
            echo "no existe el archivo.";
        endif;
    }
    
    
    //------------------------
    
    
    private function array_to_xml($array, &$xml_user_info) {
        
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    $this->array_to_xml($value, $subnode);
                }
            }else {
                $xml_user_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
        
        return $xml_user_info->asXML();
        
    }

*/

}