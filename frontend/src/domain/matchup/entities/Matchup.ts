import { Player } from '../../player/entities/Player';

export interface Matchup {
  matchup_id: number;
  winner_id: number | null;
  player1: Player;
  player2: Player;
}
