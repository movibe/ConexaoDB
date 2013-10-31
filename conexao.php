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
    private $_MySQL_server         = "localhost";
    private $_MySQL_user           = "root";
    private $_MySQL_password       = "mysql";
    private $_MySQL_database       = "teste";
    
    // Microsoft SQL Server
    private $_Mssql_server         = "";
    private $_Mssql_user           = "";
    private $_Mssql_password       = "";
    private $_Mssql_database       = "";
    
    // mSQL
    private $_mSQL_server          = "";
    private $_mSQL_user            = "";
    private $_mSQL_password        = "";
    private $_mSQL_database        = "";
    
    // SQLite
    private $_SQLite_user          = "";
    private $_SQLite_password      = "";
    private $_SQLite_database      = "";
    
    // SQLite3
    private $_SQLite3_user         = "";
    private $_SQLite3_password     = "";
    private $_SQLite3_database     = "";
    
    
    // Postgree
    private $_PostgreeSQL_server   = "";
    private $_PostgreeSQL_user     = "";
    private $_PostgreeSQL_password = "";
    private $_PostgreeSQL_database = "";
    
    // Firebird/InterBase
    private $_Firebird_user        = "";
    private $_Firebird_password    = "";
    private $_Firebird_database    = "";
    
    
    // Oracle OCI8
    private $_Oracle_server        = "";
    private $_Oracle_user          = "";
    private $_Oracle_password      = "";
    private $_Oracle_database      = "";
    
    // LDAP
    private $_ldap_server          = 'svr.domain.com';
    private $_ldap_user            = 'administrator';
    private $_ldap_pass            = 'PASSWORD_HERE';
    private $_ldap_tree            = "OU=SBSUsers,OU=Users,OU=MyBusiness,DC=myDomain,DC=local";
    private $_ldap_bind            ="";
    
    // Variaveis Principais
    public $_base                 = "mysql";
    public $_query                = "";
    public $_link                 = "";
    
    // Metodos da classe
    //Metodo Construtor

    public function __construct($base='mysql'){
        $this->_base = $base;
        $this->conectar();
    }

    // Metodo de Conexao com o banco
    public function conectar() {

        switch ($this->_base) {

            case 'mysql':
                $this->_link = mysql_connect($this->_MySQL_server, $this->_MySQL_user, $this->_MySQL_password);
              
                 if (!$this->_link ) {
                    return ("Erro de conexao");
                } elseif (!mysql_select_db($this->_MySQL_database, $this->_link)) {
                    return ("Erro na hora de selecionar o banco: ".$this->_MySQL_database);
                } else {
                    return $this->_link;
                } 

                

                break;

            case 'sqlserver':
                $this->_link = mssql_connect($this->_Mssql_server, $this->_Mssql_user, $this->_Mssql_password);
                if (!$this->_link) {
                    die("Erro de conexao: Servidor=".$this->_Mssql_server.",usuario=". $this->_Mssql_user);
                } elseif (!mssql_select_db($this->_Mssql_database, $this->_link)) {
                    die("Erro na hora de selecionar o banco ".$this->_Mssql_database);
                }   
                break;

            case 'msql':
                $this->_link = msql_connect($this->_mSQL_server, $this->_mSQL_user, $this->_mSQL_password);
                if (!$this->_link) {
                    die("Erro de conexao: Servidor=".$this->_mSQL_server.",usuario=". $this->_mSQL_user);
                } elseif (!msql_select_db($this->_sqlserver_database, $this->_link)) {
                    die("Erro na hora de selecionar o banco");
                }   
                break;

            case 'sqllite':
                 $this->_link = sqlite_open($this->_SQLite_database, 0666, $sqliteerror);
                break;

             case 'sqllite3':
                 $this->_link = PDO($this->_SQLite3_database, 0666, $sqliteerror);
                break;

            case 'postgree':
                $host  = $this->_PostgreeSQL_server;
                $user  = $this->_PostgreeSQL_user;
                $senha = $this->_PostgreeSQL_password;
                $banco = $this->_PostgreeSQL_database;
                $this->_link = pg_connect("host=$host user=$user password=$senha $dbname=$banco");
                break;

            case 'firebird':
               $this->_link = ibase_connect($this->_Firebird_database, $this->_Firebird_user, $this->_Firebird_password);
                break;

            case 'oracle':
                $this->_link = oci_connect ($this->_Oracle_user, $this->_Oracle_password, $this->_Oracle_server);
                break;

            case 'ldap':
                $this->_link = ldap_connect($this->_ldap_host, 389) or die("Not connect: $ldaphost ");
                //Setting up server query options
                ldap_set_option($lconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($lconn, LDAP_OPT_REFERRALS, 0);
                $this->_ldap_bind = @ldap_bind($lconn, "$domain\\$user", "$pass");
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

        switch ($this->_base) {
           
            case 'mysql':
                $this->_query = $query;
                return  mysql_query($this->_query);
                
                break;

            case 'sqlserver':
                $this->_query = $query;
                if ($result = mssql_query($this->_query, $this->_link) or die ('Erro no SQL: <br> <code>'.$this->_sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;
            
            case 'postgree':
                $this->_query = $query;
                if ($result = pg_exec($this->_link, $this->_query) or die ('Erro no SQL: <br> <code>'.$this->_sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'msql':
                 $this->_query = $query;
                if ($result = ibase_query($this->_link, $this->_query) or die ('Erro no SQL: <br> <code>'.$this->_sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqllite':
                 $this->_query = $query;
                if ($result = sqlite_query($this->_link, $this->_query) or die ('Erro no SQL: <br> <code>'.$this->_sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;  

            case 'sqllite3':
                 $this->_query = $query;
                 $db = $this->_link;
                if ($result = $db->exec($this->_query) or die ('Erro no SQL: <br> <code>'.$this->_query."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;
            
            case 'firebird':
                 $this->_query = $query;
                if ($result = ibase_execute($this->_query) or die ('Erro no SQL: <br> <code>'.$this->_sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'oracle':
                 $this->_query = $query;
                if ($result = oci_execute($this->_link, $this->_query) or die ('Erro no SQL: <br> <code>'.$this->_sql."</code>") ) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'ldap':
                // binding to ldap server
                $this->_ldap_bind = ldap_bind( $this->_link, $this->_ldap_prdn, $this->_ldap_ppass );

                // verify binding
                if ( $this->_ldap_bind ) {
                    $filter="uid=*";
                    $justthese = array( "uid" );

                    $sr=ldap_read( $this->_link, $srdn, $filter, $justthese );
                    return ldap_get_entries( $this->_link, $sr );

                } else {
                    return  "LDAP conn ok...";
                }
                break;
            
        }

       }

  public function mysql2table($query,$model='',$decode='false'){

         $this->_query = $query;

          $qry = mysql_query($this->_query);

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
            $this->_errors[] = 'SQLtoJSON - Cannot get number of MySQL fields';
            return false;
        }
        
        $fields = array();
        for($i = 0; $i < $numFields; $i++)
            $fields[$i] = mysql_field_name($query, $i);
        
        if(!$numRows = mysql_num_rows($query)) {
            $this->_errors[] = 'SQLtoJSON - Cannot get number of MySQL rows';
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
                $this->_errors[] = 'MySQL table fields are not the same as the JSON or are not in the same order';
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
         $this->_query = $query;
        $resultado = mysql_fetch_field($query);
        return $resultado;  
    }
    
    public function retorno($query) {
        switch ($this->_base) {

            case 'mysql':
                $this->_query = $query;
                $resultado = mysql_fetch_array($query);
                return $resultado;  
                break;

            case 'sqllite':
                 $this->_query = $query;
                $resultado = sqlite_fetch_array($query);
                return $resultado;  
                break;

            case 'sqllite3':
                $db = $this->_link;
                $resultado = $db->query($query);
                break;
            
            case 'sqlserver':
                $this->_query = $query;
                $resultado = mssql_fetch_array($query);
                return $resultado;
                break;

            case 'postgree':
                $this->_query = $query;
                $resultado = pg_fetch_array($query);
                return $resultado;
                break;

            case 'firebird':
                $this->_query = $query;
                $resultado = ibase_fetch_object($query);
                return $resultado;
                break;

            case 'msql':
                $this->_query = $query;
                $resultado = msql_fetch_array($query);
                return $resultado;
                break;

            case 'oracle':
                $this->_query = $query;
                $resultado = oci_fetch_array($query);
                return $resultado;
                break;
        }

      
    }

      // Numero de Linhas
    public function numRows($result) {
       switch ($this->_base) {
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
        //$this->_query = $query;
        $this->_query = "SELECT * FROM $tabela";

        switch ($this->_base) {
            case 'mysql':
                if ($result = mysql_query($this->_query, $this->_link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'msql':
                if ($result = msql_query($this->_query, $this->_link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqlserver':
                if ($result = mssql_query($this->_query, $this->_link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqllite':
                if ($result = sqlite_exec($this->_query, $this->_link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'sqllite3':
                if ($result = sqlite_exec($this->_query, $this->_link)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'firebird':
                if ($result = ibase_execute($this->_query)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'postgree':
                if ($result = pg_exec($this->_link, $this->_query)) {
                    return $result;
                } else {
                    return 0;
                }
                break;

            case 'oracle':
                if ($result = oci_execute($this->_query)) {
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
            return $this->_mysqlRaiseError();
        }
        return $cols;
    }

    // Metodo que retorna o ultimo id de uma inseraao
    public function mysql_lastid() {
        return mysql_insert_id($this->_link);
    }

    // Metodo fechar conexao

    public function fechar() {
        switch ($this->_base) {
            case 'mysql':
                return mysql_close($this->_link);
                break;
            
            case 'sqlserver':
                return mssql_close($this->_link);    
                break;

            case 'postgree':
                return pg_close($this->_link);
                break;


        }
        if($this->_base=='mysql'){
            return mysql_close($this->_link);
        } else if($this->_base=='sqlserver'){
            return mssql_close ($this->_link);
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
        

        $this->_query = "INSERT INTO $tabela ($campos) VALUES ($valores) $campos_key";

        if ($debug==true) {
            return $this->_query;
        } else {
           return mysql_query($this->_query) or die("Nao foi possivel inserir o registro na base: " . $this->_query);
        }
    
    }

    public function token(){
        return md5(uniqid(rand(), true));
    }

    public function mysql_delete($tabela, $where) {
        $this->_query = "DELETE FROM $tabela WHERE $where";
        return mysql_query($this->_query) or die($this->_query);
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

        $this->_query = "UPDATE $tabela SET $campos WHERE $where";

        if ($debug==true) {
            return $this->_query;
        } else {
            return mysql_query($this->_query) or die("Nao foi possivel alterar o registro na base");    
        }
        
    }


}



?>
