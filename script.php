<?php

function displayCommand(){
    echo "Missing args";
    echo "\n";
    echo "  Available :";
    echo "\n";
    echo "      insertInitial                                           //! just one time";
    echo "\n";
    echo "      createTicket, {title}, {description}, {section}          //create a new ticket";
    echo "\n";
    echo "      getTicket, {ticket title}                                //get a ticket";   
    echo "\n";
    echo "      getTickets                                              //get all tickets";
    echo "\n";
    echo "      createComment, {text}, {ticket title}                    //create a new comment";
    echo "\n";
    echo "      getComments, {ticket title}                              //get all comments";
    echo "\n";
    echo "      saveTicket, {ticket title}                              //save the ticket in a file";
    echo "\n";
    exit;
}

if(!isset($argv[1])){
    displayCommand();
}

require('./controller/ORMController.php');

array_shift($argv);
$str = join(" ", $argv);
$data = explode(",", $str);

switch($data[0])
{
    case "insertInitial":
        try{
            $orm = new ORMController();
            $orm->initialData();
            echo 'Initial data for Service ans State inserted';
        }catch(Exception $e){
            echo "data already inserted";
        }
        break;
    case "createTicket":
        if (isset($data[1]) && isset($data[2]) && isset($data[3])) {
            try{
                $orm = new ORMController();
                $new_str = str_replace(' ', '', $data[3]);
                $services = $orm->getTargetByField('service', 'libelle', $new_str);
                $orm->insertData("ticket", [
                    "title" => $data[1],
                    "description" => $data[2],
                    "service_idservice" => $services['idservice'],
                ]);
                echo 'Ticket created';
            }catch(Exception $e){
                $dataToSend= json_encode([
                    "success" => false,
                    "message" => "Db error"
                ], JSON_PRETTY_PRINT);
                echo $dataToSend;
            }
        } else {
            echo 'Missing args';
            echo "\n";
            echo "  Available :";
            echo "\n";
            echo "      createTicket {title} {description} {section}    //create a new ticket";
        }
        break;
    case "getTicket":
        if (isset($data[1])) {
            try{
                $orm = new ORMController();
                $ticket = $orm->getTargetByField('ticket','title', $data[1]);
                if ( $ticket != false && count($ticket) > 0) {
                    $dataToSend = json_encode($ticket, JSON_PRETTY_PRINT);
                    echo $dataToSend;
                } else {
                    $dataToSend = json_encode([
                        "success" => false,
                        "message" => "No ticket found"
                    ], JSON_PRETTY_PRINT);
                    echo $dataToSend;
                }
            }catch(Exception $e){
                $dataToSend= json_encode([
                    "success" => false,
                    "message" => "Ticket not found"
                ], JSON_PRETTY_PRINT);
                echo $dataToSend;
            }
        } else {
            echo 'Missing args';
            echo "\n";
            echo "  Available :";
            echo "\n";
            echo "      getTicket {ticket title}                           //get a ticket";
        }
        break;
    case "getTickets":
        try{
            $orm = new ORMController();
            $tickets = $orm->getAll('ticket');
            if ( $tickets != false && count($tickets) > 0) {
                $dataToSend = json_encode($tickets, JSON_PRETTY_PRINT);
                echo $dataToSend;
            } else {
                $dataToSend = json_encode([
                    "success" => false,
                    "message" => "No ticket found"
                ], JSON_PRETTY_PRINT);
                echo $dataToSend;
            }
        }catch(Exception $e){
            $dataToSend = json_encode([
                "success" => false,
                "message" => "No ticket found"
            ], JSON_PRETTY_PRINT);
        }
        break;
    case "createComment":
        if (isset($data[1]) && isset($data[2])) {
            try{
                $orm = new ORMController();
                $ticket = $orm->getTargetByField('ticket','title', $data[2]);
                if ( $ticket != false && count($ticket) > 0) {
                    $orm->insertData("comment", [
                        "texte" => $data[1],
                        "ticket_idticket" => $ticket['idticket'],
                    ]);
                    echo 'Comment created';
                } else {
                    $dataToSend = json_encode([
                        "success" => false,
                        "message" => "No ticket found"
                    ], JSON_PRETTY_PRINT);
                    echo $dataToSend;
                }
            }catch(Exception $e){
                $dataToSend = json_encode([
                    "success" => false,
                    "error" => "Bad request"
                ], JSON_PRETTY_PRINT);

                echo $dataToSend;
            }
        } else {
            echo 'Missing args';
            echo "\n";
            echo "  Available :";
            echo "\n";
            echo "      createComment {texte} {ticket title}                //create a new comment";
        }
        break;
    case "getComments":
        if (isset($data[1])) {
            try{
                $orm = new ORMController();
                $ticket = $orm->getTargetByField('ticket','title', $data[1]);

                $comments = $orm->getAll('comment', ['ticket_idticket' => $ticket['idticket']]);
                if ($comments != false && count($comments) > 0) {
                    $dataToSend = json_encode($comments, JSON_PRETTY_PRINT);
                    echo $dataToSend;
                } else {
                    $dataToSend = json_encode([
                        "success" => false,
                        "message" => "No comments found"
                    ], JSON_PRETTY_PRINT);
                    echo $dataToSend;
                }
            }catch(Exception $e){
                $dataToSend = json_encode([
                    "success" => false,
                    "message" => $e->getMessage()
                ], JSON_PRETTY_PRINT);
                echo $dataToSend;
            }
        } else {
            echo 'Missing args';
            echo "\n";
            echo "  Available :";
            echo "\n";
            echo "      getComments {ticket title}                         //get all comments";
        }
        break;
    case "saveTicket":
        try{
            $orm = new ORMController();
            $ticket = $orm->getTargetByField('ticket','title', $data[1]);
            if ( $ticket != false && count($ticket) > 0) {
                $myfile = fopen("SaveTicket.json", "w");
                $txt = [
                    "title" => $ticket['title'],
                    "description" => $ticket['description'],
                    "date" => $ticket['date'],
                    "comments" => []
                ];
                $comments = $orm->getAll('comment', ['ticket_idticket' => $ticket['idticket']]);
                if($comments != false && count($comments) > 0){
                    foreach ($comments as $comment) {
                        $txt['comments'][] = [
                            "texte" => $comment['texte'],
                            "date" => $comment['date']
                        ];
                    }
                }
                fwrite($myfile, json_encode($txt, JSON_PRETTY_PRINT));
                fclose($myfile);
                echo 'Ticket saved';
            } else {
                $dataToSend = json_encode([
                    "success" => false,
                    "message" => "No ticket found"
                ], JSON_PRETTY_PRINT);
                echo $dataToSend;
            }
            
        }catch(Exception $e){
            $dataToSend = json_encode([
                "success" => false,
                "message" => "Ticket not found"
            ], JSON_PRETTY_PRINT);
            echo $dataToSend;
        }
        break;
    default:
        displayCommand();
}