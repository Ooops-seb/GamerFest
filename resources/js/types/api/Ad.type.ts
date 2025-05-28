export interface Ads {
    id: number;
    title: string;
    description?: string | null;
    image_url?: string | null;
    active: boolean;
    created_at: string;
    updated_at: string;
}
