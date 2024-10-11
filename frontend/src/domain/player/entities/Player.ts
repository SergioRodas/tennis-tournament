export interface Player {
  id: number;
  name: string;
  skillLevel: number;
  gender: string;
  strength?: number;
  speed?: number;
  reactionTime?: number;
  tournamentsWon?: number;
}
