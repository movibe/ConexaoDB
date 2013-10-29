<?
/**
 * CONEXÃO COM BANCOS DE DADOS EM PHP
 *
 * @copyright Willian Ribeiro Angelo
 * @license GPL
 *
 * @author Willian Ribeiro Angelo <agfoccus@gmail.com.com>
 * @package ConexaoDB
 */


// Iniciar Conexão
// $conexao = new Conexao('mysql');

class Conexao {

    // Mysql
    private $MySQL_server         = "localhost";
    private $MySQL_user           = "root";
    private $MySQL_password       = "mysql";
    private $MySQL_database       = "teste";
    
    // Microsoft SQL Server
    private $Mssql_server         = "";
    private $Mssql_user           = "";
    private $Mssql_password       = "";
    private $Mssql_database       = "";
    
    // mSQL
    private $mSQL_server          = "";
    private $mSQL_user            = "";
    private $mSQL_password        = "";
    private $mSQL_database        = "";
    
    // SQLite
    private $SQLite_user          = "";
    private $SQLite_password      = "";
    private $SQLite_database      = "";
    
    // SQLite3
    private $SQLite3_user         = "";
    private $SQLite3_password     = "";
    private $SQLite3_database     = "";
    
    
    // Postgree
    private $PostgreeSQL_server   = "";
    private $PostgreeSQL_user     = "";
    private $PostgreeSQL_password = "";
    private $PostgreeSQL_database = "";
    
    // Firebird/InterBase
    private $Firebird_user        = "";
    private $Firebird_password    = "";
    private $Firebird_database    = "";
    
    
    // Oracle OCI8
    private $Oracle_server        = "";
    private $Oracle_user          = "";
    private $Oracle_password      = "";
    private $Oracle_database      = "";
    
    // LDAP
    private $ldap_server          = 'svr.domain.com';
    private $ldap_user            = 'administrator';
    private $ldap_pass            = 'PASSWORD_HERE';
    private $ldap_tree            = "OU=SBSUsers,OU=Users,OU=MyBusiness,DC=myDomain,DC=local";
    private $ldap_bind            ="";
    
    // Variaveis Principais
    private $base                 = "mysql";
    private $query                = "";
    private $link                 = "";


    
    // Metodos da classe
    //Metodo Construtor

    public function __construct($base='mysql'){
        // 
        $this->base = $base;
        $this->conectar();
    }

    // Metodo de Conexao com o banco
    public function conectar() {

        switch ($this->base) {

            case 'mysql':
                $this->link = mysql_connect($this->MySQL_server, $this->MySQL_user, $this->MySQL_password);
              
                 if (!$this->link ) {
                    return ("Erro de conexao");
                } elseif (!mysql_select_db($this->MySQL_database, $this->link)) {
                    return ("Erro na hora de selecionar o banco: ".$this->MySQL_database);
                } else {
                    return $this->link;
                } 

                

                break;

            case 'sqlserver':
                $this->link = mssql_connect($this->Mssql_server, $this->Mssql_user, $this->Mssql_password);
                if (!$this->link) {
                    die("Erro de conexao: Servidor=".$this->Mssql_server.",usuario=". $this->Mssql_user);
                } elseif (!mssql_select_db($this->Mssql_database, $this->link)) {
                    die("Erro na hora de selecionar o banco ".$this->Mssql_database);
                }   
                break;

            case 'msql':
                $this->link = msql_connect($this->mSQL_server, $this->mSQL_user, $this->mSQL_password);
                if (!$this->link) {
                    die("Erro de conexao: Servidor=".$this->mSQL_server.",usuario=". $this->mSQL_user);
                } elseif (!msql_select_db($this->sqlserver_database, $this->link)) {
                    die("Erro na hora de selecionar o banco");
                }   
                break;

            case 'sqllite':
                 $this->link = sqlite_open($this->SQLite_database, 0666, $sqliteerror);
                break;

             case 'sqllite3':
                 $this->link = PDO($this->SQLite3_database, 0666, $sqliteerror);
                break;

            case 'postgree':
                $host  = $this->PostgreeSQL_server;
                $user  = $this->PostgreeSQL_user;
                $senha = $this->PostgreeSQL_password;
                $banco = $this->PostgreeSQL_database;
                $this->link = pg_connect("host=$host user=$user password=$senha $dbname=$banco");
                break;

            case 'firebird':
               $this->link = ibase_connect($this->Firebird_database, $this->Firebird_user, $this->Firebird_password);
                break;

            case 'oracle':
                $this->link = oci_connect ($this->Oracle_user, $this->Oracle_password, $this->Oracle_server);
                break;

            case 'ldap':
                $this->link = ldap_connect($this->ldap_host, 389) or die("Not connect: $ldaphost ");
                //Setting up server query options
                ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($lconn, LDAP_OPT_REFERRALS, 0);
                $this->ldap_bind = @ldap_bind($lconn, "$domain\\$user", "$pass");
                break;
        }

        
    }

     public function sql_clean($string) {
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = mysql_real_escape_string($string);
        return $string;
    } 
    

    // Metodo sql
    public function sql($query) {

        switch ($this->base) {
           
            case 'mysql':
                $this->query = $query;
                return  mysql_query($this->query);
                
                break;

            case 'sqlserver':
                $this->query = $query;
                if ($result = mssql_query($this->query, $this->link) or die ('Erro no SQL: <br> <code>'.$this->sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;
            
            case 'postgree':
                $this->query = $query;
                if ($result = pg_exec($this->link, $this->query) or die ('Erro no SQL: <br> <code>'.$this->sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'msql':
                 $this->query = $query;
                if ($result = ibase_query($this->link, $this->query) or die ('Erro no SQL: <br> <code>'.$this->sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqllite':
                 $this->query = $query;
                if ($result = sqlite_query($this->link, $this->query) or die ('Erro no SQL: <br> <code>'.$this->sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;  

            case 'sqllite3':
                 $this->query = $query;
                 $db = $this->link;
                if ($result = $db->exec($this->query) or die ('Erro no SQL: <br> <code>'.$this->query."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;
            
            case 'firebird':
                 $this->query = $query;
                if ($result = ibase_execute($this->query) or die ('Erro no SQL: <br> <code>'.$this->sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'oracle':
                 $this->query = $query;
                if ($result = oci_execute($this->link, $this->query) or die ('Erro no SQL: <br> <code>'.$this->sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'ldap':
                // binding to ldap server
                $this->ldap_bind = ldap_bind( $this->link, $this->ldap_prdn, $this->ldap_ppass );

                // verify binding
                if ( $this->ldap_bind ) {
                    $filter="uid=*";
                    $justthese = array( "uid" );

                    $sr=ldap_read( $this->link, $srdn, $filter, $justthese );
                    return ldap_get_entries( $this->link, $sr );

                } else {
                    return  "LDAP conn ok...";
                }
                break;
            
        }

       }

  public function mysql2table($query,$model='',$decode='false'){

         $this->query = $query;

          $qry = mysql_query($this->query);

          //Pegando os nomes dos campos
          $num_fields = mysql_num_fields($qry);

          //Obtém o número de campos do resultado
          for($i = 0;$i<$num_fields; $i++){//Pega o nome dos campos
            $fields[] = mysql_field_name($qry,$i);
          }

          $table = "<script type='text/javascript'>
            $('.sorting').each(public function(i){
                private coluna = $(this).attr('aria-label');
                // Aplica a cor de fundo
                $(this).addClass(coluna);
            });
        </script>";
          //Montando o cabeçalho da tabela
          // $table .= "<table class='table table-striped table-bordered $model' url='".info_filename()."'>";
          $table .= "<table class='table table-striped table-bordered $model' url=''>";
          $table .="<thead>";
          $table .= '<tr>';
          for($i = 0;$i < $num_fields; $i++){
            $table .= '<th class="sorting" aria-sort="ASC" aria-label="'.$fields[$i].'">'.$fields[$i].'</th>';
          }
          $table .="</thead>";

          //Montando o corpo da tabela
          $table .= '<tbody>';
          while($r = mysql_fetch_array($qry)){
            $table .= '<tr>';

            for($i = 0;$i < $num_fields; $i++){
                $valor = $r[$fields[$i]];

                if ($decode==false){
                    $valor = utf8_decode($valor);
                } else {
                    $valor = utf8_encode($valor); 
                }

                if(is_numeric($valor)){
                    $class ='right';    
                } else {
                    $class ='left';
                }
                
                switch ($fields[$i]) {
                    case 'Image':
                        $table .= '<td class="center">'.avatar($valor,50).'</td>';    
                        break;
                    
                    case 'Status':
                        $table .= '<td class="'.$class.'">'.get_code('status',$valor,'').'</td>';
                        break;

                    default:
                        $table .= '<td class="'.$class.'">'.($valor).'</td>';  
                        break;
                }

        
              
            }
            $table .= '</tr>';
          }

          //Finalizando a tabela
          $table .= '</tbody></table>';

          return $table;
      }
    // Transforma o Select em Json
    public function mysql2json($query, $indented = false) {
        $query = mysql_query($query) or die ('MyJSON - SQLtoJSON - Cannot make query');
        
        if(!$numFields = mysql_num_fields($query)) {
            $this->errors[] = 'SQLtoJSON - Cannot get number of MySQL fields';
            return false;
        }
        
        $fields = array();
        for($i = 0; $i < $numFields; $i++)
            $fields[$i] = mysql_field_name($query, $i);
        
        if(!$numRows = mysql_num_rows($query)) {
            $this->errors[] = 'SQLtoJSON - Cannot get number of MySQL rows';
            return 0;
        }
        
        $res = array();
        for($i = 0; $i < $numRows; $i++) {
            $res[$i] = array();
            for($j = 0; $j < count($fields); $j++)
                $res[$i][$fields[$j]] = mysql_result($query, $i, $j);
        }
        
        $json = json_encode($res);
        if($indented == false)
            return $json;
        
        $result = '';
        $pos = 0;
        $previous = '';
        $outQuotes = true;

        for ($i=0; $i <= strlen($json); $i++) {

            // Next char
            $char = substr($json, $i, 1);

            // Inside quote?
            if ($char == '"' && $previous != '\\') {
                $outQuotes = !$outQuotes;
            
            // End of element? New line and indent
            } elseif(($char == '}' || $char == ']') && $outQuotes) {
                $result .= "\n";
                $pos--;
                for ($j=0; $j<$pos; $j++)
                    $result .= '    ';
            }
            
            // Add the character to the result string.
            $result .= $char;

            // Beginning of element? New line and indent
            if (($char == ',' || $char == '{' || $char == '[') && $outQuotes) {
                $result .= "\n";
                if ($char == '{' || $char == '[')
                    $pos++;
                
                for ($j = 0; $j < $pos; $j++)
                    $result .= '    ';
            }
            
            $previous = $char;
        }
        
        return $result;
    }

    //This assumes an open database connection 
    //I also use a constant DB_DB for current database. 
    public function GetFieldInfo($table) { 
      if($table == '') return false; 
      $fields = mysql_list_fields(DB_DB, $table); 
      if($fields){ 
         
      } 
      return false; 
    } 

    public function datatable($query){
        
        $sql = mysql_query($query) or die ('MyJSON - SQLtoJSON - Cannot make query');

         if ( mysql_num_rows( $sql )>0 ) {
        // prepare output for DataTables
        $data = array( "sEcho" =>intval( mysql_num_rows( $sql ) ),
          "aoColumns" =>array(),
          "aaData" =>array()
        );

        while ( $linha = mysql_fetch_field( $sql ) ) {
          $data['aoColumns'][]['sTitle'] = $linha->name;
        }

        // Body
        while ( $result = mysql_fetch_array( $sql ) ) {
          $data['aaData'][] = $result;
        }
        return json_encode( $data );
      } else {
        return json_encode( array( 'total' => 0 ) );
      }

    }
    
    public function sql2json($json, $table) {
        $tmpjson = json_decode($json);
        $json = array();
        foreach($tmpjson as $index => $value) {
            $json[$index] = (array)$value;
        }
        
        $json_fields = array();
        foreach($json[0] as $field => $value) {
            $json_fields[] = $field;
        }
        
        
        // Get MySQL rows
        $sel = mysql_query("SELECT * FROM $table") or die ('MyJSON - JSONtoSQL - Cannot get MySQL rows');
        
        $rows = array();
        for($i = 0; $i < mysql_num_fields($sel); $i++)
            $rows[$i] = mysql_field_name($sel, $i);
        
        // Test recived data....
        for($i = 0; $i < count($rows); $i++) {
            if($rows[$i] != $json_fields[$i]) {
                $this->errors[] = 'MySQL table fields are not the same as the JSON or are not in the same order';
                return false;
            }
        }
        
        // All ok, make query....
        $qry = "INSERT INTO $table(";
        
        foreach($rows as $row)
            $qry .= "`$row`, ";
            
        $qry = substr($qry, 0, strlen($qry) - 2).') VALUES (';
        
        foreach($json as $field => $value) {
            $values = null;
            
            foreach($value as $n_field => $n_value) {
                if(empty($n_value))
                    $values .= 'NULL, ';
                elseif(is_numeric($n_value))
                    $values .= "$n_value, ";
                else
                    $values .= "'$n_value', ";
            }
            
            $queries[] = $qry.substr($values, 0, strlen($values) -2).')';
        }
        
        foreach($queries as $query)
            mysql_query($query) or die ('MyJSON - JSONtoSQL - Query failed: '.mysql_error());
        
        return true;
    } 
    public function colunas($query){
         $this->query = $query;
        $resultado = mysql_fetch_field($query);
        return $resultado;  
    }
    
    public function retorno($query) {
        switch ($this->base) {

            case 'mysql':
                $this->query = $query;
                $resultado = mysql_fetch_array($query);
                return $resultado;  
                break;

            case 'sqllite':
                 $this->query = $query;
                $resultado = sqlite_fetch_array($query);
                return $resultado;  
                break;

            case 'sqllite3':
                $db = $this->link;
                $resultado = $db->query($query);
                break;
            
            case 'sqlserver':
                $this->query = $query;
                $resultado = mssql_fetch_array($query);
                return $resultado;
                break;

            case 'postgree':
                $this->query = $query;
                $resultado = pg_fetch_array($query);
                return $resultado;
                break;

            case 'firebird':
                $this->query = $query;
                $resultado = ibase_fetch_object($query);
                return $resultado;
                break;

            case 'msql':
                $this->query = $query;
                $resultado = msql_fetch_array($query);
                return $resultado;
                break;

            case 'oracle':
                $this->query = $query;
                $resultado = oci_fetch_array($query);
                return $resultado;
                break;
        }

      
    }

      // Numero de Linhas
    public function numRows($result) {
       switch ($this->base) {
           case 'mysql':
                $rows = @mysql_num_rows($result);
                if ($rows === null) {
                    return 0;
                }
                return $rows;
               break;

            case 'sqlite':
                 $rows = @sqlite_num_rows ($result);
                if ($rows === null) {
                    return 0;
                }
                return $rows;
                break;

            case 'sqlite3':
                #não tem suporte 
                break;

            case 'sqlserver':
                 $rows = @mssql_num_rows($result);
                if ($rows === null) {
                    return 0;
                }
                return $rows;
                break;

            case 'postgree':
                $rows = @pg_num_rows($result);
                if ($rows === null) {
                    return 0;
                }
                return $rows;
                break;
           
          case 'firebird':
             $rows = @ibase_num_fields($result);
                if ($rows === null) {
                    return 0;
                }
                return $rows;
              break;

            case 'oracle':
                $rows = @oci_num_rows ($result);
                if ($rows === null) {
                    return 0;
                }
                return $rows;
                break;
       }
    }

    // Metodo all
    public function mysql_all($tabela) {
        //$this->query = $query;
        $this->query = "SELECT * FROM $tabela";

        switch ($this->base) {
            case 'mysql':
                if ($result = mysql_query($this->query, $this->link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'msql':
                if ($result = msql_query($this->query, $this->link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqlserver':
                if ($result = mssql_query($this->query, $this->link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqllite':
                if ($result = sqlite_exec($this->query, $this->link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqllite3':
                if ($result = sqlite_exec($this->query, $this->link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'firebird':
                if ($result = ibase_execute($this->query)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'postgree':
                if ($result = pg_exec($this->link, $this->query)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'oracle':
                if ($result = oci_execute($this->query)) {
                    return $result;
                } else {
                    return 0;
                }
                break;
            
            default:
                # code...
                break;
        }
       
    }

        // Numero de Colunas    
    public function numCols($result) {
        $cols = @mysql_num_fields($result);
        if (!$cols) {
            return $this->mysqlRaiseError();
        }
        return $cols;
    }

    // Metodo que retorna o ultimo id de uma inseraao
    public function mysql_lastid() {
        return mysql_insert_id($this->link);
    }

    // Metodo fechar conexao

    public function fechar() {
        switch ($this->base) {
            case 'mysql':
                return mysql_close($this->link);
                break;
            
            case 'sqlserver':
                return mssql_close($this->link);    
                break;

            case 'postgree':
                return pg_close($this->link);
                break;


        }
        if($this->base=='mysql'){
            return mysql_close($this->link);
        } else if($this->base=='sqlserver'){
            return mssql_close ($this->link);
        }
    }

    public function mysql_insert($tabela, $dados,$duplicate_key='',$debug=false) {
        // Colunas e Valores
       $qtd = count($dados);
        $i=0;
        foreach ($dados as $key => $value) {
        $i++;
            if($qtd==$i){
                $campos .= "`$key`";
                $valores .= "'$value'";
            } else {
                $campos .= "`$key`,";
                $valores .= "'$value',";
            }
        }

        if ($duplicate_key!='') {
              $qtda = count($duplicate_key);
            if($qtda>0){
                $campos_key = 'ON DUPLICATE KEY UPDATE ';
            }
            $a=0;
            foreach ($duplicate_key as $key => $value) {
            $a++;
                if($qtda==$a){
                    $campos_key .= "$key='$value'";
                } else {
                    $campos_key .= "$key='$value',";
                }
            }
        }
        

        $this->query = "INSERT INTO $tabela ($campos) VALUES ($valores) $campos_key";

        if ($debug==true) {
            return $this->query;
        } else {
           return mysql_query($this->query) or die("Nao foi possivel inserir o registro na base: " . $this->query);
        }
    
    }

    public function token(){
        return md5(uniqid(rand(), true));
    }

    public function mysql_delete($tabela, $where) {
        $this->query = "DELETE FROM $tabela WHERE $where";
        return mysql_query($this->query) or die($this->query);
    }

    public function mysql_update($tabela, $dados, $where, $debug=false) {

        $qtd = count($dados);
        $i=0;
        foreach ($dados as $key => $value) {
        $i++;
            if($qtd==$i){
                $campos .= "$key='$value'";
            } else {
                $campos .= "$key='$value',";
            }
        }

        $this->query = "UPDATE $tabela SET $campos WHERE $where";

        if ($debug==true) {
            return $this->query;
        } else {
            return mysql_query($this->query) or die("Nao foi possivel alterar o registro na base");    
        }
        
    }


}



?>
