export const getBlockCpt = async (type: string) => {
    return resolveBlockComponent(type, import.meta.glob('../components/blocks/**/*.vue'));
};

export async function resolveBlockComponent<T>(blockType: string, blocks: Record<string, Promise<T> | (() => Promise<T>)>): Promise<T> {
    const normalized = blockType.toLowerCase();

    for (const path in blocks) {
        const fileName = path
            .split('/')
            .pop()
            ?.replace('Block.vue', '')
            .replace(/([a-z])([A-Z])/g, '$1-$2')
            .toLowerCase();

        console.log(fileName);

        if (!fileName) continue;

        if (fileName === normalized) {
            const block = blocks[path];
            return await (typeof block === 'function' ? block() : block);
        }
    }

    throw new Error(`Block not found: ${blockType}`);
}
