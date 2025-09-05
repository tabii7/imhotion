#!/usr/bin/env bash
# Save chat from stdin into chat_exports directory with a timestamped filename by default.
set -euo pipefail

# Directory layout: repo-root/scripts/save_chat.sh -> ../chat_exports
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT_DIR="$ROOT_DIR/chat_exports"
mkdir -p "$OUT_DIR"

# First argument optional filename. If omitted, use timestamped name.
FNAME="${1:-chat-$(date +%Y%m%d-%H%M%S).txt}"
TARGET="$OUT_DIR/$FNAME"

# Read stdin and write to file
cat - > "$TARGET"
echo "Saved chat to: $TARGET"

exit 0
