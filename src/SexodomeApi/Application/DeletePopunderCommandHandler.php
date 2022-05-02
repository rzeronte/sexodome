<?php

namespace Sexodome\SexodomeApi\Application;

use App\Model\Popunder;
use Sexodome\Shared\Application\Command\CommandHandler;

class DeletePopunderCommandHandler implements CommandHandler
{
    public function execute($popunder_id)
    {
        try {
            $popunder = Popunder::find($popunder_id);

            if (!$popunder) {
                return [ 'status' => false, 'message' => "Popunder $popunder_id not found" ];
            }

            $popunder->delete();

            return ['status' => $status = true ];
        } catch (\Exception $e) {
            return ['status' => $status = false, 'message' => $e->getMessage() ];
        }
    }
}
