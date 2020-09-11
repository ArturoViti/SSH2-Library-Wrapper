<?php
    /**
     * @author Arturo Viti <vitiarturo@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @version 2.0.0
     * @since 2.0.0 revisione del software a oggetti
     * Libreria a oggetti per la gestione di un canale SSH.
     */ 

    namespace lib\ssh2;
    class SSH2
    {
        private $connessione;
        private $stream;
        private $stream_out;
        private $output_string;
        private $shell;
        private $line;

        /**
         * Inizializza la connessione con il server
         *
         * @param $ip
         * @param $port
         * @param $username
         * @param $password
         */

        public function __construct( $ip, $port, $username, $password )
        {
            $this->connessione = ssh2_connect( $ip, $port );
            if ( !($this->connessione) )
                throw new \Exception('Server non Raggiungibile.');
            else
                if ( !(ssh2_auth_password( $this->connessione, $username, $password )) )
                    throw new \Exception('Autenticazione non Riuscita.');
        }

        /**
         *  Disconnette il client dal server.
         */
        public function __destruct()
        {
            if ( !(ssh2_disconnect( $this->connessione )) )
                throw new \Exception('Disconnessione non riuscita');
            unset( $this->connessione );
        }

        /**
         * Istanzia una shell
         */
        public function getShell()
        {
            $this->shell = ssh2_shell( $this->connessione, 'xterm', 
                                    null, 80, 24, SSH2_TERM_UNIT_CHARS );
        }

        /**
         * Esegue il comando passato sul server
         *
         * @param $command
         */

        public function execute( $command )
        {
            $this->stream = ssh2_exec( $this->connessione, $command );
            if ( !($this->stream) )
                throw new \Exception('Errore nel comando');
        }

        /**
         * Restituisce l'output del comando
         *
         * @return string
         */
        public function getCommandOutput()
        {
            if ( $this->stream )
            {
                //Impostazione Bloccante
                stream_set_blocking( $this->stream, true );
                //Lettura dello stream STDIO
                $this->stream_out = ssh2_fetch_stream( $this->stream, SSH2_STREAM_STDIO );
                //Salvataggio dei dati letti sul canale
                $this->output_string = (string) stream_get_contents( $this->stream_out );
                //L'output ritorna a \n, che HTML 5 non riconosce, pertanto:
                $this->output_string = str_replace( "\n", "<br/>", $this->output_string );
                return $this->output_string;
            }
            else
                throw new \Exception('Errore nella lettura dello stream');
        }

                /**
         * Restituisce l'output del comando
         *
         * @return string
         */
        public function getCommandOutput2()
        {
            if ( $this->stream )
            {
                //Impostazione Bloccante
                stream_set_blocking( $this->stream, true );
                //Lettura dello stream STDIO
                $this->stream_out = ssh2_fetch_stream( $this->stream, SSH2_STREAM_STDIO );
                //Salvataggio dei dati letti sul canale
                $this->output_string = (string) stream_get_contents( $this->stream_out );
                return $this->output_string;
            }
            else
                throw new \Exception('Errore nella lettura dello stream');
        }

    }
?>
