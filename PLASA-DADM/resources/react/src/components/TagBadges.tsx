import { Badge } from "@/components/ui/badge";

interface TagBadgesProps {
  tags?: string[];
  documentId: string;
  className?: string;
}

export function TagBadges({ tags, documentId, className = "" }: TagBadgesProps) {
  if (!tags || tags.length === 0) return null;

  return (
    <div className={`flex flex-wrap gap-2 mt-1 mb-1 ${className}`}>
      {tags.map((tag, index) => (
        <Badge
          key={index}
          variant="secondary"
          className="px-2 py-1 text-xs font-medium"
          data-testid={`tag-${tag.toLowerCase()}-${documentId}`}
        >
          {tag}
        </Badge>
      ))}
    </div>
  );
}