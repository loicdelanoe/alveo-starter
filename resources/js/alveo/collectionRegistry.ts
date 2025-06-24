export const getCollectionCpt = async (type: string) => {
    return resolveCollectionComponent(type, import.meta.glob('../components/collections/**/*.vue'));
};

export async function resolveCollectionComponent<T>(
    collectionType: string,
    collections: Record<string, Promise<T> | (() => Promise<T>)>,
): Promise<T> {
    const normalized = collectionType.toLowerCase();

    for (const path in collections) {
        const fileName = path.split('/').pop()?.replace('.vue', '').toLowerCase();

        if (!fileName) continue;

        if (fileName === normalized) {
            const collection = collections[path];
            return await (typeof collection === 'function' ? collection() : collection);
        }
    }

    throw new Error(`Collection not found: ${collectionType}`);
}
