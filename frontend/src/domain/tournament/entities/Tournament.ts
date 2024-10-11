export interface Tournament {
  id: number;
  name: string;
  gender: string;
  winner: {
    id: number;
    name: string;
    skillLevel: number;
    gender: string;
    strength: number;
    speed: number;
    reactionTime: number | null;
  } | null;
  created_at: string;
  finished_at: string | null;
}
